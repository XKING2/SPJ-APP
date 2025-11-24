<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test Chat</title>
</head>
<body>
    <h1>Test Chat Page</h1>
    <p>Buka console untuk melihat event.</p>

    <script src="https://js.pusher.com/8.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1/dist/echo.iife.js"></script>

    <script>
        // Pastikan config Pusher sesuai .env
        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env("PUSHER_APP_KEY") }}',
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            forceTLS: false
        });

        // Gunakan public channel untuk tes cepat
        Echo.channel('test-channel')
            .listen('.TestEvent', (e) => {
                console.log('Event diterima:', e.message);
                alert('Event diterima: ' + e.message);
            });

        alert('Script berjalan'); // cek JS dijalankan
    </script>
</body>
</html>
