import ability from '../../libs/acl/ability';

export default function authorized({next, to, store}) {
	if (to.meta.permission && to.meta.permission.action && to.meta.permission.subject) {
		if (ability.cannot(to.meta.permission.action, to.meta.permission.subject)) {
			next({name: "error-403"});
			return false;
		}
	}

	next();
}