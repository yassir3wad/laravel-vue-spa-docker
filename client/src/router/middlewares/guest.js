import Vue from "vue";

export default async function guest({next, store}) {
	if (store.getters["auth/checked"] === false) {
		store.commit("auth/SET_CHECKED")
		await store.dispatch("auth/user");
	}

	if (store.getters["auth/authenticated"]) {
		next({name: "home"});
		return false;
	}

	next();
}