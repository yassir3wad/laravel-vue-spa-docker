import Vue from 'vue'
import Vuex from 'vuex'

// Modules
import app from './app'
import appConfig from './app-config'
import verticalMenu from './vertical-menu'
import auth from './auth'

Vue.use(Vuex)

export default new Vuex.Store({
	modules: {
		app,
		appConfig,
		verticalMenu,
		auth,
	},
	strict: process.env.NODE_ENV !== 'production'
})
