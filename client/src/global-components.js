import Vue from 'vue'

import FeatherIcon from '@core/components/feather-icon/FeatherIcon.vue'

Vue.component(FeatherIcon.name, FeatherIcon)


import vSelect from 'vue-select'

require('@core/scss/vue/libs/vue-select.scss')
Vue.component("v-select", vSelect);
