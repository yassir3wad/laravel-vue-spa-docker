import ensureCsrfTokenSet from "@/router/middlewares/ensureCsrfTokenSet";
import authenticated from "@/router/middlewares/authenticated";
import authorized from "@/router/middlewares/authorized";

const routes = [];
const modulesRoutes = [
	{name: 'users', singular: 'user'},
	{name: 'roles', singular: 'role'},
];
modulesRoutes.forEach(function (item) {
	routes.push({
		path: '/' + item.name,
		name: `${item.name}.index`,
		meta: {
			middleware: [ensureCsrfTokenSet, authenticated, authorized],
			resource: item.name,
			permission: {
				action: 'viewAny',
				subject: item.singular,
			}
		},
		component: () => import(`@/views/pages/${item.name}/List.vue`),
	});

	routes.push({
		path: `/${item.name}/create`,
		name: `${item.name}.create`,
		meta: {
			middleware: [ensureCsrfTokenSet, authenticated, authorized],
			resource: item.name,
			permission: {
				action: 'create',
				subject: item.singular,
			}
		},
		component: () => import(`@/views/pages/${item.name}/Form.vue`),
	});

	routes.push({
		path: `/${item.name}/:id/edit`,
		name: `${item.name}.edit`,
		meta: {
			middleware: [ensureCsrfTokenSet, authenticated, authorized],
			resource: item.name,
			permission: {
				action: 'update',
				subject: item.singular,
			}
		},
		component: () => import(`@/views/pages/${item.name}/Form.vue`),
	});
})

export default routes;