@extends('layouts.app')

@section('title', 'FCM token')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4 space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900">Тест FCM (web)</h1>
        <p class="mt-1 text-sm text-slate-500">
            Получает FCM-токен у браузера, регистрирует его на бэке и шлёт тестовый пуш.
            Доступно только админам.
        </p>
    </div>

    {{-- ── Step 1: get FCM token ──────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
        <h2 class="text-sm font-semibold text-slate-900">1. Получить FCM-токен и зарегистрировать</h2>

        <button id="getTokenBtn"
                class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
            <i class="fas fa-bell mr-2"></i>Запросить разрешение и получить токен
        </button>

        <div id="getStatus" class="text-sm text-slate-600"></div>

        <div id="tokenWrap" class="hidden space-y-2">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">FCM token</p>
            <textarea id="tokenOut"
                      readonly
                      rows="3"
                      class="w-full px-3 py-2 text-xs font-mono bg-slate-50 border border-slate-200 rounded-lg break-all"></textarea>
        </div>
    </div>

    {{-- ── Step 2: send test push ─────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
        <h2 class="text-sm font-semibold text-slate-900">2. Отправить тестовый пуш</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <label class="block">
                <span class="text-xs font-medium text-slate-500">Заголовок</span>
                <input id="pushTitle" type="text" value="Тест"
                       class="mt-1 block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </label>
            <label class="block">
                <span class="text-xs font-medium text-slate-500">Тело</span>
                <input id="pushBody" type="text" value="Привет от Silk Way"
                       class="mt-1 block w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </label>
        </div>

        <button id="sendPushBtn"
                disabled
                class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-lg transition-colors">
            <i class="fas fa-paper-plane mr-2"></i>Отправить себе пуш
        </button>

        <div id="sendStatus" class="text-sm text-slate-600"></div>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800">
        <strong>Важно:</strong> на проде нужен HTTPS — без TLS браузер не разрешит push.
        На localhost работает и без https.
    </div>
</div>

<script type="module">
    import { initializeApp }   from "https://www.gstatic.com/firebasejs/10.13.2/firebase-app.js";
    import { getMessaging, getToken, onMessage }
        from "https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging.js";

    const firebaseConfig = {
        apiKey:            "AIzaSyA8IOqEZ6w_1tX3pvLf_REY-i7mnOVcOpk",
        authDomain:        "fruck-kz.firebaseapp.com",
        projectId:         "fruck-kz",
        storageBucket:     "fruck-kz.firebasestorage.app",
        messagingSenderId: "1076024786935",
        appId:             "1:1076024786935:web:0b3f398a1f1790242938f2",
    };

    const VAPID_PUBLIC_KEY = "{{ config('services.firebase_web.vapid_key') }}";
    const API_TOKEN        = @json($apiToken);
    const ME_ID            = {{ (int) $userId }};

    const app       = initializeApp(firebaseConfig);
    const messaging = getMessaging(app);

    const getStatus  = document.getElementById('getStatus');
    const sendStatus = document.getElementById('sendStatus');
    const tokenOut   = document.getElementById('tokenOut');
    const tokenWrap  = document.getElementById('tokenWrap');
    const sendBtn    = document.getElementById('sendPushBtn');

    let fcmToken = null;

    async function api(method, url, body = null) {
        const res = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'Authorization': 'Bearer ' + API_TOKEN,
            },
            body: body ? JSON.stringify(body) : null,
        });
        const text = await res.text();
        let json;
        try { json = text ? JSON.parse(text) : null; } catch { json = { raw: text }; }
        return { ok: res.ok, status: res.status, body: json };
    }

    document.getElementById('getTokenBtn').addEventListener('click', async () => {
        getStatus.textContent = 'Запрашиваем разрешение…';

        if (!VAPID_PUBLIC_KEY) {
            getStatus.innerHTML = '<span class="text-rose-600">VAPID-ключ не настроен (FIREBASE_WEB_VAPID_KEY в .env).</span>';
            return;
        }

        try {
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                getStatus.innerHTML = '<span class="text-rose-600">Разрешение не выдано: ' + permission + '</span>';
                return;
            }

            const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');

            fcmToken = await getToken(messaging, {
                vapidKey: VAPID_PUBLIC_KEY,
                serviceWorkerRegistration: registration,
            });

            if (!fcmToken) {
                getStatus.innerHTML = '<span class="text-rose-600">Браузер вернул пустой токен.</span>';
                return;
            }

            tokenOut.value = fcmToken;
            tokenWrap.classList.remove('hidden');
            getStatus.textContent = 'Регистрируем токен на сервере…';

            const reg = await api('POST', '/api/v1/push-tokens', { token: fcmToken, platform: 'web' });
            if (!reg.ok) {
                getStatus.innerHTML = '<span class="text-rose-600">Регистрация не прошла: HTTP ' + reg.status + ' — ' + JSON.stringify(reg.body) + '</span>';
                return;
            }

            getStatus.innerHTML = '<span class="text-emerald-600">Токен получен и зарегистрирован.</span>';
            sendBtn.disabled = false;
        } catch (err) {
            console.error(err);
            getStatus.innerHTML = '<span class="text-rose-600">Ошибка: ' + (err.message || err) + '</span>';
        }
    });

    sendBtn.addEventListener('click', async () => {
        sendStatus.textContent = 'Отправляем…';
        const title = document.getElementById('pushTitle').value.trim() || 'Тест';
        const body  = document.getElementById('pushBody').value.trim()  || '';

        const res = await api('POST', '/api/v1/admin/push/test', { user_id: ME_ID, title, body });
        if (!res.ok) {
            sendStatus.innerHTML = '<span class="text-rose-600">HTTP ' + res.status + ' — ' + JSON.stringify(res.body) + '</span>';
            return;
        }

        const r = res.body;
        if (r.delivered_to_devices > 0) {
            sendStatus.innerHTML = '<span class="text-emerald-600">Отправлено на ' + r.delivered_to_devices + ' устройств(а). Жди уведомление.</span>';
        } else {
            const failed = (r.results || []).filter(x => x.status === 'failed');
            const errs = failed.map(x => '<div class="mt-2"><b>token #' + x.token_id + ' (' + (x.platform || '?') + ')</b>: ' + (x.error || 'unknown') + (x.firebase_code ? ' <code class="bg-rose-100 px-1 rounded">' + x.firebase_code + '</code>' : '') + '</div>').join('');
            sendStatus.innerHTML =
                '<div class="text-amber-700">Не доставлено. <code>token_count_before</code>=' + r.token_count_before + ', <code>after</code>=' + r.token_count_after + '.</div>'
                + errs;
        }
    });

    // Foreground messages — показываем системную нотификацию когда вкладка активна.
    onMessage(messaging, (payload) => {
        console.log('FCM foreground:', payload);
        if (Notification.permission === 'granted' && payload.notification) {
            new Notification(payload.notification.title || 'Silk Way', {
                body: payload.notification.body || '',
                icon: '/favicon.ico',
            });
        }
    });
</script>
@endsection
