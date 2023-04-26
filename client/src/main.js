import Vue from 'vue'
import VueCompositionAPI from '@vue/composition-api'
import mixins from '@core/mixins/mixins'
import {BootstrapVue} from 'bootstrap-vue'

import { ValidationObserver, ValidationProvider, extend, localize } from 'vee-validate';
import * as rules from 'vee-validate/dist/rules';
import en from 'vee-validate/dist/locale/en.json';

import i18n from '@/libs/i18n'
import router from './router'
import store from './store'
import App from './App.vue'

// Global Components
import './global-components'

// 3rd party plugins
import '@axios'
import '@core/libs/sokets'
import '@/libs/acl'
import '@/libs/portal-vue'
// import '@/libs/clipboard'
import '@/libs/toastification'
// import '@/libs/sweet-alerts'
import '@/libs/vue-select'
import {onRejected} from '@/libs/axios/interceptors'
// import '@/libs/tour'

Vue.use(BootstrapVue)

// Composition API
Vue.use(VueCompositionAPI)

// Feather font icon - For form-wizard
// * Shall remove it if not using font-icons of feather-icons - For form-wizard
require('@core/assets/fonts/feather/iconfont.css') // For form-wizard

// import core styles
require('@core/scss/core.scss')

// import assets styles
require('@/assets/scss/style.scss')

Vue.config.productionTip = false

// Vue.prototype.$http.interceptors.response.use(undefined, (error) => onRejected(error, store, router))

Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
Object.keys(rules).forEach((rule) => {
	extend(rule, {
		...rules[rule],
	});
});
localize({
	en: {
		messages: en.messages,
	},
});

Vue.mixin(mixins);

new Vue({
	router,
	store,
	i18n,
	render: h => h(App),
}).$mount('#app')