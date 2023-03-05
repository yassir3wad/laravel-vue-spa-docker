import Vue from "vue";

export default function ensureCsrfTokenSet({next, store}) {
	if (!document.cookie.includes('XSRF-TOKEN')) {
		store.dispatch('auth/generateCsrfToken');
	}

	next();
}