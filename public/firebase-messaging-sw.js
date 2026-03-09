importScripts(
    "https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js",
);
importScripts(
    "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js",
);

const firebaseConfig = {
    apiKey: "AIzaSyARdxiCWKqHepOhC8rks9zH-2c7aNeme-A",
    authDomain: "sorah-travel.firebaseapp.com",
    projectId: "sorah-travel",
    storageBucket: "sorah-travel.firebasestorage.app",
    messagingSenderId: "1025589669532",
    appId: "1:1025589669532:web:f966a945912b6213dec759",
};

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {
    self.registration.showNotification(payload.notification.title, {
        body: payload.notification.body,
        data: { url: payload.data.url },
    });
});

self.addEventListener("notificationclick", function (event) {
    event.notification.close();
    event.waitUntil(clients.openWindow(event.notification.data.url));
});
