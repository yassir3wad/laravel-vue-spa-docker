import ensureCsrfTokenSet from "@/router/middlewares/ensureCsrfTokenSet";
import authenticated from "@/router/middlewares/authenticated";

const routes = [];
const modulesRoutes = [
	{name: 'users', singular: 'user'},
];
modulesRoutes.forEach(function (item) {
	routes.push({
		path: '/' + item.name,
		name: `${item.name}.index`,
		meta: {
			middleware: [ensureCsrfTokenSet, authenticated],
			resource: item.name,
			permission: {
				parent: item.name,
				permission: 'viewAny user',
			}
		},
		component: () => import(`@/views/pages/${item.name}/List.vue`),
	});

	routes.push({
		path: `/${item.name}/create`,
		name: `${item.name}.create`,
		meta: {
			middleware: [ensureCsrfTokenSet, authenticated],
			resource: item.name,
			permission: {
				parent: item.name,
				permission: 'create user',
			}
		},
		component: () => import(`@/views/pages/${item.name}/Form.vue`),
	});

	routes.push({
		path: `/${item.name}/:id/edit`,
		name: `${item.name}.edit`,
		meta: {
			middleware: [ensureCsrfTokenSet, authenticated],
			resource: item.name,
			permission: {
				parent: item.name,
				permission: 'update user',
			}
		},
		component: () => import(`@/views/pages/${item.name}/Form.vue`),
	});
})

export default routes;