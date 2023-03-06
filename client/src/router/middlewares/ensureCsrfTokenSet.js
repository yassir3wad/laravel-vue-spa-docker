export default async function ensureCsrfTokenSet({next, store}) {
	if (!document.cookie.includes('XSRF-TOKEN')) {
		await store.dispatch('auth/generateCsrfToken');
	}

	next();
}