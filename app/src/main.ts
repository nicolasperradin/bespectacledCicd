import { createApp } from 'vue'
import VCalendar from 'v-calendar'

import 'bootstrap'
import 'v-calendar/dist/style.css'
import 'bootstrap/dist/css/bootstrap.min.css'

import App from './App.vue'
import store from './store'
import router from './router'
import pinia from './plugins/pinia'
import FaIcon from './plugins/icons'
import VueI18n from './plugins/i18n'
import vuetify from './plugins/vuetify'

createApp(App)
	.use(store)
	.use(pinia)
	.use(router)
	.use(vuetify)
	.use(VueI18n)
	.use(VCalendar)
	.component('fa-icon', FaIcon)
	.mount('#app')