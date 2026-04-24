# WhatsApp HTTP API (WAHA)

Self-hosted WhatsApp REST API на базе [WAHA](https://waha.devlike.pro/). Поднят в Docker, проброшен через Cloudflare Tunnel.

## Базовая конфигурация

| Параметр | Значение |
|---|---|
| Base URL (public) | `https://wa.fruck.kz` |
| Base URL (local) | `http://localhost:3000` |
| Auth header | `X-Api-Key: <API_KEY>` |
| Session name | `default` |
| Engine | NOWEB |
| Content-Type | `application/json` |

**API-ключ хранится в переменной окружения** — никогда не коммить в репозиторий:

```bash
export WAHA_URL=https://wa.fruck.kz
export WAHA_API_KEY=<your-key>
```

## Формат `chatId`

| Тип | Формат | Пример |
|---|---|---|
| Личный чат | `<phone>@c.us` | `77001200013@c.us` |
| Группа | `<group_id>@g.us` | `120363043...@g.us` |
| Канал | `<channel>@newsletter` | `120363...@newsletter` |

**Phone**: код страны + номер, только цифры, без `+` и пробелов.
- ✅ `77001200013`
- ❌ `+7 700 120 00 13`, `87001200013` (внутренний формат не работает)

ID группы получить через `GET /api/default/chats`.

## Статусы сессии

| Status | Значит |
|---|---|
| `STOPPED` | Выключена. Вызови `POST /api/sessions/{name}/start` |
| `STARTING` | Загружается |
| `SCAN_QR_CODE` | Ждёт сканирования QR |
| `WORKING` | Готова отправлять/принимать |
| `FAILED` | Ошибка — смотри логи |

## Эндпоинты

Все требуют `X-Api-Key`. Все `chatId` — по правилам выше.

### Отправка

#### Текст
```http
POST /api/sendText
Content-Type: application/json

{
  "session": "default",
  "chatId": "77001200013@c.us",
  "text": "Привет",
  "reply_to": "3EB0...",   // опционально, id исходного сообщения
  "linkPreview": true,     // опционально
  "mentions": ["77001200013@c.us"]  // опционально
}
```
**Возврат:** `201` + объект сообщения с `key.id`.

#### Картинка
```http
POST /api/sendImage
{
  "session": "default",
  "chatId": "77001200013@c.us",
  "file": { "url": "https://example.com/pic.jpg" },
  "caption": "подпись"
}
```
Альтернатива — base64: `"file": {"mimetype": "image/jpeg", "data": "<base64>"}`.

#### Файл / документ
```http
POST /api/sendFile
{
  "session": "default",
  "chatId": "77001200013@c.us",
  "file": { "url": "https://example.com/doc.pdf", "filename": "doc.pdf" },
  "caption": "отчёт"
}
```

#### Голосовое
```http
POST /api/sendVoice
{ "session":"default", "chatId":"...", "file": {"url":"https://.../voice.ogg"}, "convert": true }
```

#### Геолокация
```http
POST /api/sendLocation
{ "session":"default", "chatId":"...", "latitude": 43.238, "longitude": 76.889, "title": "Алматы" }
```

#### Реакция на сообщение
```http
PUT /api/reaction
{ "session":"default", "messageId":"false_77001200013@c.us_3EB0...", "reaction": "👍" }
```

#### "Печатает..." / "записывает голосовое"
```http
POST /api/startTyping
{ "session":"default", "chatId":"77001200013@c.us" }

POST /api/stopTyping
{ "session":"default", "chatId":"77001200013@c.us" }
```

#### Отметить прочитанным
```http
POST /api/sendSeen
{ "session":"default", "chatId":"77001200013@c.us" }
```

### Чтение

#### Список чатов
```http
GET /api/{session}/chats?limit=20&offset=0
```

#### История сообщений в чате
```http
GET /api/{session}/chats/{chatId}/messages?limit=50&downloadMedia=false
```
`chatId` — URL-encoded (`%40` вместо `@`).

#### Проверить, есть ли номер в WhatsApp
```http
GET /api/{session}/contacts/check-exists?phone=77001200013
```
Вернёт `{ "numberExists": true, "chatId": "77001200013@c.us" }`.

#### Информация о контакте
```http
GET /api/{session}/contacts?contactId=77001200013@c.us
```

### Управление сессией

#### Статус
```http
GET /api/sessions/{session}
```

#### Старт / стоп / перезапуск
```http
POST /api/sessions/{session}/start
POST /api/sessions/{session}/stop
POST /api/sessions/{session}/restart
POST /api/sessions/{session}/logout
```

#### QR-код (только когда `status = SCAN_QR_CODE`)
```http
GET /api/{session}/auth/qr?format=image   # PNG
GET /api/{session}/auth/qr?format=raw     # JSON с base64
```

## Webhooks (приём входящих)

WAHA умеет слать входящие сообщения на твой URL. Настраивается через `PUT /api/sessions/{session}` с `config.webhooks[].url`:

```json
{
  "config": {
    "webhooks": [
      {
        "url": "https://your-app.example.com/wa-hook",
        "events": ["message", "message.ack", "session.status"],
        "hmac": { "key": "<shared-secret>" }
      }
    ]
  }
}
```

На `message` прилетит JSON с `payload.from`, `payload.body`, `payload.id`, `payload.timestamp`.
HMAC-подпись в заголовке `X-Webhook-Hmac` — проверяй на своей стороне.

## Коды ответов

| Код | Причина |
|---|---|
| `200` / `201` | OK |
| `400` | Кривой JSON / невалидные поля |
| `401` | Нет / неверный `X-Api-Key` |
| `404` | Чат/сообщение не найдено |
| `422` | Сессия в неподходящем статусе (напр. QR запрашивается когда `WORKING`) |
| `500` | WAHA упал — смотри логи контейнера |

## Примеры

### curl
```bash
curl -X POST "$WAHA_URL/api/sendText" \
  -H "X-Api-Key: $WAHA_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"session":"default","chatId":"77001200013@c.us","text":"Привет"}'
```

### Python (sync, requests)
```python
import os, requests

WAHA_URL = os.environ["WAHA_URL"]
WAHA_API_KEY = os.environ["WAHA_API_KEY"]

def send_text(phone: str, text: str, session: str = "default") -> dict:
    chat_id = f"{''.join(c for c in phone if c.isdigit())}@c.us"
    r = requests.post(
        f"{WAHA_URL}/api/sendText",
        headers={"X-Api-Key": WAHA_API_KEY},
        json={"session": session, "chatId": chat_id, "text": text},
        timeout=30,
    )
    r.raise_for_status()
    return r.json()

send_text("77001200013", "Привет")
```

### Python (async, httpx)
```python
import os, httpx

class WAHA:
    def __init__(self, url: str | None = None, api_key: str | None = None, session: str = "default"):
        self.url = url or os.environ["WAHA_URL"]
        self.api_key = api_key or os.environ["WAHA_API_KEY"]
        self.session_name = session
        self._client = httpx.AsyncClient(
            base_url=self.url,
            headers={"X-Api-Key": self.api_key},
            timeout=30,
        )

    async def send_text(self, phone_or_chat: str, text: str) -> dict:
        chat_id = phone_or_chat if "@" in phone_or_chat else f"{''.join(c for c in phone_or_chat if c.isdigit())}@c.us"
        r = await self._client.post("/api/sendText", json={
            "session": self.session_name, "chatId": chat_id, "text": text,
        })
        r.raise_for_status()
        return r.json()

    async def check_number(self, phone: str) -> bool:
        r = await self._client.get(
            f"/api/{self.session_name}/contacts/check-exists",
            params={"phone": phone},
        )
        r.raise_for_status()
        return r.json().get("numberExists", False)

    async def status(self) -> str:
        r = await self._client.get(f"/api/sessions/{self.session_name}")
        r.raise_for_status()
        return r.json()["status"]

    async def close(self):
        await self._client.aclose()
```

### Node.js (fetch)
```javascript
const WAHA_URL = process.env.WAHA_URL;
const WAHA_API_KEY = process.env.WAHA_API_KEY;

export async function sendText(phone, text, session = "default") {
  const chatId = phone.includes("@") ? phone : `${phone.replace(/\D/g, "")}@c.us`;
  const r = await fetch(`${WAHA_URL}/api/sendText`, {
    method: "POST",
    headers: {
      "X-Api-Key": WAHA_API_KEY,
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ session, chatId, text }),
  });
  if (!r.ok) throw new Error(`WAHA ${r.status}: ${await r.text()}`);
  return r.json();
}
```

## Ограничения и ошибки

- **Один аккаунт = одна сессия.** WhatsApp позволяет 4 связанных устройства; WAHA — одно из них.
- **Не подходит для массовых рассылок.** WhatsApp банит за паттерны спама (много одинаковых сообщений незнакомым номерам, высокая частота). Для транзакционных уведомлений / ответов боту — ок.
- **Номер должен быть в WhatsApp.** Используй `check-exists` перед отправкой.
- **Рестарт контейнера** не требует повторной авторизации — сессия персистится в `./sessions`.
- **Потеря сессии** (разлогинило из телефона / истёк срок) → `status: SCAN_QR_CODE`, нужен новый QR.

---

## Промт для агента / LLM

Вставь в системный промт проекта:

> Ты — ассистент, у которого есть инструмент для отправки сообщений в WhatsApp через самостоятельно хостимый API WAHA.
>
> **Endpoint:** `https://wa.fruck.kz`
> **Auth:** заголовок `X-Api-Key` (значение из `WAHA_API_KEY` env).
> **Session:** `default`.
>
> **Правила использования:**
> 1. Номера телефонов приводи к формату `<country_code><number>@c.us` без `+`, пробелов и дефисов. Пример: `+7 (700) 120-00-13` → `77001200013@c.us`.
> 2. Перед первой отправкой на незнакомый номер вызови `GET /api/default/contacts/check-exists?phone=<digits>` — если `numberExists: false`, не отправляй и верни пользователю ошибку.
> 3. Для отправки текста: `POST /api/sendText` с телом `{"session":"default","chatId":"<chatId>","text":"<message>"}`.
> 4. Для картинок/файлов: `POST /api/sendImage` или `POST /api/sendFile` с `file.url` (публичный URL) или `file.data` (base64) + `file.mimetype`.
> 5. Сообщения должны быть строго по запросу пользователя. Не отправляй ничего «от себя», не рассылай, не повторяй то же сообщение на несколько номеров без явного на то указания.
> 6. Если API вернул `4xx`/`5xx` — покажи пользователю полное тело ответа, не повторяй запрос автоматически.
> 7. Если статус сессии не `WORKING` (получается через `GET /api/sessions/default`) — останови операцию и сообщи пользователю, что нужно переподключить WhatsApp через QR.
>
> **Не делай:**
> - Не логируй `X-Api-Key` в выводе пользователю.
> - Не используй API для рассылки незапрошенных сообщений — это нарушает ToS WhatsApp и может привести к бану аккаунта.
> - Не предполагай, что номер получателя в WhatsApp — всегда проверяй через `check-exists` для новых контактов.

---

## Файлы в репо (для справки)

- `docker-compose.yml` — конфиг WAHA
- `sessions/` — персистентная сессия WhatsApp (не коммить!)
- `wa-send` (в `~/.local/bin/`) — shell-обёртка над API
