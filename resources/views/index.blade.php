<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sorah Travel</title>
</head>

<body>
    <h1>Hello, Sorah Travel</h1>
    <script type="module">
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import {
            getMessaging,
            getToken,
            onMessage
        } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";

        const firebaseConfig = {
            apiKey: "AIzaSyARdxiCWKqHepOhC8rks9zH-2c7aNeme-A",
            authDomain: "sorah-travel.firebaseapp.com",
            projectId: "sorah-travel",
            storageBucket: "sorah-travel.firebasestorage.app",
            messagingSenderId: "1025589669532",
            appId: "1:1025589669532:web:f966a945912b6213dec759"
        };

        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);

        navigator.serviceWorker.register('/firebase-messaging-sw.js')
            .then(async (registration) => {

                try {

                    const permission = await Notification.requestPermission();

                    console.log("Permission:", permission);

                    if (permission !== "granted") {
                        alert("Permission not granted");
                        return;
                    }

                    const currentToken = await getToken(messaging, {
                        vapidKey: "{{ env('FCM_VAPID_KEY') }}",
                        serviceWorkerRegistration: registration
                    });

                    console.log("TOKEN:", currentToken);

                    if (currentToken) {
                        alert("Token Generated");
                        alert(currentToken);
                    } else {
                        alert("No token received");
                    }

                } catch (error) {
                    console.error("Error getting token:", error);
                    alert("Error check console");
                }
            });

        onMessage(messaging, (payload) => {
            console.log("Foreground message:", payload);
        });
    </script>
</body>

</html>
