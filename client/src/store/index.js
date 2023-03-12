import Vue from 'vue'
import Vuex from 'vuex'

// Modules
import app from './app'
import appConfig from './app-config'
import verticalMenu from './vertical-menu'
import auth from './auth.store'
import breadcrumbs from './breadcrumbs.store'

Vue.use(Vuex)

export default new Vuex.Store({
	modules: {
		app,
		appConfig,
		verticalMenu,
		auth,
		breadcrumbs,
	},
	strict: process.env.NODE_ENV !== 'production'
})
