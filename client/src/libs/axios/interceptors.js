export const onRejected = (error, store, router) => {
	if (error) {
		const originalRequest = error.config;
		if (error.response.status === 401 && !originalRequest._retry) {
			originalRequest._retry = true;
			return router.push({name: 'login'});
		}
	}
}