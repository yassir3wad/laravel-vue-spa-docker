import Vue from 'vue'
import Toast from 'vue-toastification'

// Toast Notification Component Styles
import '@core/scss/vue/libs/toastification.scss'

/**
 * NOTE: If you are using other transition them make sure to import it in `src/@core/scss/vue/libs/notification.scss` from it's source
 */
Vue.use(Toast, {
  position: "top-right",
  timeout: 3000,
  closeOnClick: true,
  pauseOnFocusLoss: true,
  pauseOnHover: true,
  draggable: true,
  draggablePercent: 0.6,
  showCloseButtonOnHover: false,
  hideProgressBar: true,
  closeButton: "button",
  icon: true,
  rtl: false,
  transition: 'Vue-Toastification__fade',
})
