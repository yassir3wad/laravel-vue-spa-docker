import Vue from 'vue'
import {initialAbility} from "@/libs/acl/config";

export default {
	namespaced: true, state: {
		checked: false, user: null,
	}, getters: {
		checked(state) {
			return state.checked
		}, authenticated(state) {
			return !!state.user;
		}, user(state) {
			return state.user
		}
	},
	mutations: {
		SET_CHECKED(state) {
			state.checked = true
		},
		SET_USER(state, value) {
			state.user = value;
		},
		SET_PERMISSIONS(state, permissions) {
			if (permissions && permissions.length) {
				Vue.prototype.$ability.update(permissions.map(p => {
					const arr = p.split(' ');
					return {
						action: arr[0],
						subject: arr[1],
					};
				}));
			} else {
				Vue.prototype.$ability.update([initialAbility]);
			}
		}
	},
	actions: {
		async generateCsrfToken() {
			return await Vue.prototype.$http.get('/sanctum/csrf-cookie');
		}, async login({commit, dispatch}, form) {
			await Vue.prototype.$http.post('/api/login', form);
			return dispatch('user');
		}, async user({commit}) {
			return await Vue.prototype.$http.get('/api/me').then(({data}) => {
				const user = Object.assign({}, data.data);
				delete user.permissions;
				commit('SET_USER', user)
				commit('SET_PERMISSIONS', data.data.permissions)
			}).catch(() => {
				commit('SET_USER', null);
				commit('SET_PERMISSIONS', [])
			}).finally(() => {
				commit('SET_CHECKED', true)
			});
		}, logout({commit}) {
			return Vue.prototype.$http.post('/api/logout').finally(() => {
				commit('SET_USER', null);
				commit('SET_PERMISSIONS', [])
			});
		}
	}
}