import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store'
import middleware from './middlewares/middleware'
import ensureCsrfTokenSet from './middlewares/ensureCsrfTokenSet'
import authenticated from './middlewares/authenticated';
import AuthRoutes from "@/router/routes/AuthRoutes";
import ModulesRoutes from "@/router/routes/ModulesRoutes";

Vue.use(VueRouter)


const router = new VueRouter({
	mode: 'history',
	base: process.env.BASE_URL,
	scrollBehavior() {
		return {x: 0, y: 0}
	},
	routes: [
		{
			path: '/',
			name: 'home',
			component: () => import('@/views/Home.vue'),
			meta: {
				middleware: [ensureCsrfTokenSet, authenticated],
			},
		},
		{
			path: '/profile',
			name: 'profile',
			component: () => import('@/views/account-setting/AccountSetting.vue'),
			meta: {
				middleware: [ensureCsrfTokenSet, authenticated],
			},
		},
		...AuthRoutes,
		...ModulesRoutes,
		{
			path: '/error-404',
			name: 'error-404',
			component: () => import('@/views/error/Error404.vue'),
			meta: {
				layout: 'full',
			},
		},
		{
			path: '/error-403',
			name: 'error-403',
			component: () => import('@/views/error/Error403.vue'),
			meta: {
				layout: 'full',
			},
		},
		{
			path: '*',
			component: () => import('@/views/error/Error404.vue'),
			meta: {
				layout: 'full',
			},
		},
	],
})

router.beforeEach(middleware({store}))

// ? For splash screen
// Remove afterEach hook if you are not using splash screen
router.afterEach(() => {
	// Remove initial loading
	const appLoading = document.getElementById('loading-bg')
	if (appLoading) {
		appLoading.style.display = 'none'
	}
})

export default router
