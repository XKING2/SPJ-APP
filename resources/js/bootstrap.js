import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
  broadcaster: 'reverb',
  key: import.meta.env.VITE_REVERB_APP_KEY,        // misal Vite / env kamu
  wsHost: import.meta.env.VITE_REVERB_HOST,         // host Reverb
  wsPort: import.meta.env.VITE_REVERB_PORT,         // misal 8080
  wssPort: import.meta.env.VITE_REVERB_PORT,        // sama kalau pakai TLS
  forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
  enabledTransports: ['ws', 'wss'],
});
