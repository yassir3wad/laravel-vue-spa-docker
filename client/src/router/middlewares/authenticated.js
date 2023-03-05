import Vue from "vue";

export default async function authenticated({next, store}) {
	// fetch user
	if (store.getters["auth/checked"] === false) {
		store.commit("auth/SET_CHECKED")
		await store.dispatch("auth/user");
	}

	if (!store.getters["auth/authenticated"]) {
		next({name: "login"});
		return false;
	}

	next();
}