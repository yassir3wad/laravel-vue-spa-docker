import Vue from 'vue'

export default {
	namespaced: true,
	state: {
		checked: false,
		user: null,
	},
	getters: {
		checked(state) {
			return state.checked
		},
		authenticated(state) {
			return !!state.user;
		},
		user(state) {
			return state.user
		}
	},
	mutations: {
		SET_CHECKED(state) {
			state.checked = true
		},
		SET_USER(state, value) {
			state.user = value
		}
	},
	actions: {
		generateCsrfToken() {
			return Vue.prototype.$http.get('/sanctum/csrf-cookie').then();
		},
		async login({commit, dispatch}, form) {
			await Vue.prototype.$http.post('/login', form);
			return dispatch('user');
		},
		async user({commit}) {
			return Vue.prototype.$http.get('/api/user').then(({data}) => {
				commit('SET_USER', data)
			}).catch(() => {
				commit('SET_USER', null)
			}).finally(() => {
				commit('SET_CHECKED', true)
			});
		},
		logout({commit}) {
			return Vue.prototype.$http.post('/logout').finally(() => {
				commit('SET_USER', null);
			});
		}
	}
}