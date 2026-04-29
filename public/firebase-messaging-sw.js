// Firebase Cloud Messaging service worker.
// Must be served from the domain root (/firebase-messaging-sw.js) so the
// default scope ("/") covers the whole site. Do not move it under /js/ etc.

importScripts("https://www.gstatic.com/firebasejs/10.13.2/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging-compat.js");

firebase.initializeApp({
    apiKey: "AIzaSyA8IOqEZ6w_1tX3pvLf_REY-i7mnOVcOpk",
    authDomain: "fruck-kz.firebaseapp.com",
    projectId: "fruck-kz",
    storageBucket: "fruck-kz.firebasestorage.app",
    messagingSenderId: "1076024786935",
    appId: "1:1076024786935:web:0b3f398a1f1790242938f2",
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    const title = payload.notification?.title || "Silk Way";
    const options = {
        body: payload.notification?.body || "",
        icon: "/favicon.ico",
        data: payload.data || {},
    };
    self.registration.showNotification(title, options);
});
