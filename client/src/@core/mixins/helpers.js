export function getCookie(name, fallback = null) {
	var b = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
	return b ? b.pop() : fallback;
}

export function setCookie(cname, value, expiryDays) {
	var d = new Date();
	d.setTime(d.getTime() + expiryDays * 24 * 60 * 60 * 1000);
	var expires = 'expires=' + d.toUTCString();
	document.cookie = cname + '=' + value + ';' + expires + ';path=/';
}


export function resolveStatusVariant(status) {
	if (status === 'active') return 'success'
	if (status === 'inactive') return 'secondary'

	return 'primary'
}

