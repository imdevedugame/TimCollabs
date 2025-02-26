import './bootstrap';
import './calendar.js';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import '@fortawesome/fontawesome-free/css/all.min.css';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY, 
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});
