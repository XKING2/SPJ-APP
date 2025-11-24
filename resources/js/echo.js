import Echo from "laravel-echo";
import Pusher from "pusher-js";
import axios from "axios";

// Pusher default global
window.Pusher = Pusher;

// Configure axios default (pastikan baseURL diarahkan ke Laravel API)
axios.defaults.withCredentials = true; // jika menggunakan cookie-based auth (sanctum)
axios.defaults.baseURL = import.meta.env.VITE_API_URL || "http://localhost:8000";

export const echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY || 'reverb-key',
  wsHost: import.meta.env.VITE_REVERB_HOST || window.location.hostname,
  wsPort: Number(import.meta.env.VITE_REVERB_PORT || 6001),
  forceTLS: false,
  encrypted: false,
  enabledTransports: ['ws','wss'],
  // jika butuh authEndpoint (default /broadcasting/auth)
  authEndpoint: `${axios.defaults.baseURL}/broadcasting/auth`,
  // headers auth, mis. token
  // auth: { headers: { Authorization: `Bearer ${TOKEN}` } }
});

export default echo;
