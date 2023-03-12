import ensureCsrfTokenSet from "@/router/middlewares/ensureCsrfTokenSet";
import guest from "@/router/middlewares/guest";

export default [
	{
		path: '/login',
		name: 'login',
		component: () => import('@/views/auth/Login.vue'),
		meta: {
			layout: 'full',
			middleware: [ensureCsrfTokenSet, guest],
			title: 'Login'
		},
	},
	{
		path: '/forget-password',
		name: 'forget_password',
		component: () => import('@/views/auth/ForgotPassword-v1.vue'),
		meta: {
			layout: 'full',
			middleware: [ensureCsrfTokenSet, guest],
			title: 'Forget Password'
		},
	}, {
		path: '/reset-password',
		name: 'reset_password',
		component: () => import('@/views/auth/ResetPassword-v1.vue'),
		meta: {
			layout: 'full',
			middleware: [ensureCsrfTokenSet, guest],
			title: 'Reset Password'
		},
	},
];