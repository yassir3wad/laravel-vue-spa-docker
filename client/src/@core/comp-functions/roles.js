import Vue from 'vue';
import {groupBy} from 'lodash';
import {ref, onMounted, computed} from '@vue/composition-api';
import {useUtils as useI18nUtils} from '@core/libs/i18n'

export const getPermissions = () => {
	const permissions = ref([]);

	onMounted(() => {
		Vue.prototype.$http.get('/api/roles/permissions')
			.then(({data}) => {
				permissions.value = data.data;
			})
			.catch();
	})

	const totalPermissions = computed(() => {
		return permissions.value.length;
	})

	const groupedPermissions = computed(() => {
		return groupBy(permissions.value, 'group')
	})

	return {
		totalPermissions,

		groupedPermissions,

		// data goes here
		permissions,
	}
}

export const getRoleStrengthVariant = (percent) => {
	percent = parseInt(percent);
	if (percent === 100) {
		return 'success';
	} else if (percent >= 80) {
		return 'info';
	} else if (percent >= 60) {
		return 'warning';
	} else if (percent >= 40) {
		return 'secondary';
	}

	return 'danger'
};

export const getRoles = () => {
	const roles = ref([]);

	onMounted(() => {
		Vue.prototype.$http.get('/api/roles/list')
			.then(({data}) => {
				roles.value = data.data;
			})
			.catch();
	})


	return {
		// data goes here
		roles,
	}
}


export function checkPermissionsSelection() {
	const selectedAll = ref([]);
	const selected = ref([]);

	const {t} = useI18nUtils()

	const labelSelectUnSelect = (item) => {
		return !selectedAll.value.includes(item) ? t('select_all') : t('unselect');
	}

	const onChangeSelect = (item, groupedPermissions) => {
		if (selectedAll.value.includes(item)) {
			let list = _.map(groupedPermissions[item], 'id');
			list.forEach((x) => {
				if (selected.value.indexOf(x) < 0) selected.value.push(x);
			});
			checkForSelectAll(item, groupedPermissions);
		} else {
			let list = _.map(groupedPermissions[item], 'id');
			list.forEach((x) => {
				selected.value.splice(selected.value.indexOf(x), 1);
			});
		}
	}

	const checkForSelectAll = (item, groupedPermissions) => {
		let arr1 = selected.value;
		let arr2 = _.map(groupedPermissions[item], 'id');
		let allFounded = _.every(arr2, ai => arr1.includes(ai));
		if (allFounded) {
			if (!selectedAll.value.includes(item)) selectedAll.value.push(item);
		} else {
			selectedAll.value.splice(selectedAll.value.indexOf(item), 1);
		}
	}

	const checkAllSelected = (groupedPermissions) => {
		let arr1 = selected.value;
		Object.keys(groupedPermissions).forEach((item) => {
			let arr2 = _.map(groupedPermissions[item], 'id');
			let allFounded = _.every(arr2, ai => arr1.includes(ai));
			if (allFounded && !selectedAll.value.includes(item)) {
				selectedAll.value.push(item);
			}
			if (!allFounded && selectedAll.value.includes(item)) {
				selectedAll.value.splice(selectedAll.value.indexOf(item), 1);
			}
		});
	}

	return {
		selectedAll,
		selected,

		labelSelectUnSelect,
		onChangeSelect,
		checkAllSelected,
	};
}