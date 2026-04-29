@extends('layouts.app')

@section('title', 'FCM token')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4 space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900">Получение FCM-токена (web)</h1>
        <p class="mt-1 text-sm text-slate-500">
            Страница для разработчиков. Запрашивает у браузера разрешение на пуши,
            получает FCM-токен и показывает его. Скопируй и зарегистрируй через
            <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs">POST /api/v1/push-tokens</code>.
        </p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
        <button id="getTokenBtn"
                class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors">
            <i class="fas fa-bell mr-2"></i>Запросить разрешение и получить токен
        </button>

        <div id="status" class="text-sm text-slate-600"></div>

        <div id="tokenWrap" class="hidden space-y-2">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">FCM token</p>
            <textarea id="tokenOut"
                      readonly
                      rows="4"
                      class="w-full px-3 py-2 text-xs font-mono bg-slate-50 border border-slate-200 rounded-lg break-all"></textarea>
            <button id="copyBtn"
                    class="inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg transition-colors">
                <i class="fas fa-copy mr-1.5"></i>Скопировать
            </button>
        </div>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800">
        <strong>Важно:</strong> на проде HTTPS обязателен (FCM работает только с https://, на http:// браузер откажет).
        В localhost всё работает и без TLS.
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

    // VAPID public key — get it from Firebase Console:
    // Project Settings → Cloud Messaging → Web configuration → Web Push certificates → Generate key pair (or copy existing).
    const VAPID_PUBLIC_KEY = "{{ config('services.firebase_web.vapid_key') }}";

    const app       = initializeApp(firebaseConfig);
    const messaging = getMessaging(app);

    const statusEl = document.getElementById('status');
    const tokenOut = document.getElementById('tokenOut');
    const tokenWrap = document.getElementById('tokenWrap');

    document.getElementById('getTokenBtn').addEventListener('click', async () => {
        statusEl.textContent = 'Запрашиваем разрешение…';

        if (!VAPID_PUBLIC_KEY) {
            statusEl.innerHTML = '<span class="text-rose-600">VAPID-ключ не настроен. Заполни <code>FIREBASE_WEB_VAPID_KEY</code> в <code>.env</code>.</span>';
            return;
        }

        try {
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                statusEl.innerHTML = '<span class="text-rose-600">Разрешение не выдано: '+permission+'</span>';
                return;
            }

            const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');

            const token = await getToken(messaging, {
                vapidKey: VAPID_PUBLIC_KEY,
                serviceWorkerRegistration: registration,
            });

            if (!token) {
                statusEl.innerHTML = '<span class="text-rose-600">Токен пустой — браузер не вернул значение.</span>';
                return;
            }

            tokenOut.value = token;
            tokenWrap.classList.remove('hidden');
            statusEl.innerHTML = '<span class="text-emerald-600">Токен получен.</span>';
        } catch (err) {
            console.error(err);
            statusEl.innerHTML = '<span class="text-rose-600">Ошибка: '+(err.message || err)+'</span>';
        }
    });

    document.getElementById('copyBtn').addEventListener('click', () => {
        navigator.clipboard.writeText(tokenOut.value);
        document.getElementById('copyBtn').innerHTML = '<i class="fas fa-check mr-1.5"></i>Скопировано';
    });

    // Foreground messages (когда вкладка открыта и активна).
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
