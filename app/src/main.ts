import { createApp } from 'vue'
import VCalendar from 'v-calendar'
// import { StripePlugin } from '@vue-stripe/vue-stripe';

import 'bootstrap'
import 'v-calendar/dist/style.css'
import 'bootstrap/dist/css/bootstrap.min.css'

import App from './App.vue'
import router from './router'
import pinia from './plugins/pinia'
import VueI18n from './plugins/i18n'
import vuetify from './plugins/vuetify'

createApp(App)
	.use(pinia)
	.use(router)
	.use(vuetify)
	.use(VueI18n)
	.use(VCalendar)
	.mount('#app')