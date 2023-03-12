import Vue from 'vue'
import axios from 'axios'
import {getCookie} from '@core/mixins/helpers';

const axiosIns = axios.create({
	// You can add your headers here
	// ================================
	baseURL: process.env.VUE_APP_SERVER_URL,
	// timeout: 1000,
	headers: {
		'X-Requested-With': 'XMLHttpRequest',
		'Accept': 'application/json',
		'Accept-Language': getCookie('locale', 'en')
	},
	withCredentials: true,
});


Vue.prototype.$http = axiosIns

export default axiosIns
