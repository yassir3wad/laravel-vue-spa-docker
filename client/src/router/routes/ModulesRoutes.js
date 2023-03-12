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
			title: 'Users',
			resource: item.name,
			permission: {
				parent: item.name,
				permission: 'viewAny user',
			},
			breadcrumb: [
				{
					text: 'Users',
					active: true,
				}
			]
		},
		component: () => import(`@/views/pages/${item.name}/List.vue`),
	});

	routes.push({
		path: `/${item.name}/create`,
		name: `${item.name}.create`,
		meta: {
			middleware: [ensureCsrfTokenSet, authenticated],
			title: 'Create User',
			resource: item.name,
			permission: {
				parent: item.name,
				permission: 'create user',
			},
			breadcrumb: [
				{
					text: 'Users',
					active: true,
				},
				{
					text: 'Create',
					active: true,
				}
			]
		},
		component: () => import(`@/views/pages/${item.name}/Form.vue`),
	});

	routes.push({
		path: `/${item.name}/:id/edit`,
		name: `${item.name}.update`,
		meta: {
			middleware: [ensureCsrfTokenSet, authenticated],
			title: 'Update User',
			resource: item.name,
			permission: {
				parent: item.name,
				permission: 'update user',
			},
			breadcrumb: [
				{
					text: 'Users',
					active: true,
				},
				{
					text: 'Update',
					active: true,
				}
			]
		},
		component: () => import(`@/views/pages/${item.name}/Form.vue`),
	});
})

export default routes;