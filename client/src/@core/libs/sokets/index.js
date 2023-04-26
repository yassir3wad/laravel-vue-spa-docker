import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const options = {
	key: process.env.VUE_APP_PUSHER_APP_KEY,
	wsHost: process.env.VUE_APP_PUSHER_HOST,
	wsPort: process.env.VUE_APP_PUSHER_PORT,
	wssPort: process.env.VUE_APP_PUSHER_PORT,
	cluster: 'mt1',
	forceTLS: false,
	encrypted: true,
	disableStats: true,
	enabledTransports: ['ws', 'wss'],
};

const laravelEcho = new Echo({
	broadcaster: 'pusher', ...options, client: new Pusher(options.key, options)
});

laravelEcho.listen('all', '.welcome', e => {
	console.log('ssss', e);
});

export default laravelEcho;