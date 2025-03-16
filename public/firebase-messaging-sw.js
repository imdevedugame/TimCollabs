importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js');

const firebaseConfig = {
    apiKey: "AIzaSyCbMt2Srn5mfq1veEOaKoiVStWS40GMpoo",
    authDomain: "latihan-a885e.firebaseapp.com",
    projectId: "latihan-a885e",
    storageBucket: "latihan-a885e.firebasestorage.app",
    messagingSenderId: "666921673981",
    appId: "1:666921673981:web:3f3900257f1c17402f4a94",
    measurementId: "G-DJN215YXP6"
};

// Initialize Firebase
const app = firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/logo.png',
        data: payload.data || {}
    };

    return self.registration.showNotification(notificationTitle, notificationOptions);
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const urlToOpen = new URL(event.notification.data.url || '/', self.location.origin).href;
    
    event.waitUntil(
        clients.matchAll({type: 'window'}).then(windowClients => {
            let matchingClient = null;
            
            for (let client of windowClients) {
                if (client.url === urlToOpen) {
                    matchingClient = client;
                    break;
                }
            }

            if (matchingClient) {
                return matchingClient.focus();
            } else {
                return clients.openWindow(urlToOpen);
            }
        })
    );
});