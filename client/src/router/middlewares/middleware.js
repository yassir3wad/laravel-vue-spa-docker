export default (store) => (to, from, next) => {

	if (to.meta?.middleware?.length) {
		const arr = to.meta.middleware
		for (let index = 0; index < arr.length; index++) {
			const method = arr[index];
			const result = method({...store, to, from, next})
			if (result === false) {
				break;
			}
		}
		return
	}

	return next()
}