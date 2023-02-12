import { createApp } from 'vue'
import VCalendar from 'v-calendar'

import 'bootstrap'
import 'v-calendar/dist/style.css'
import 'bootstrap/dist/css/bootstrap.min.css'

import App from './App.vue'
import store from './store'
import router from './router'
import { FaIcon, vuetify } from './plugins/libraries'
import { vScrollTo, vNotify } from './plugins/directives'

createApp(App)
	.use(store)
	.use(router)
	.use(vuetify)
	.use(VCalendar)
	.component('fa-icon', FaIcon)
	.directive('scroll-to', vScrollTo)
	.directive('notify', vNotify)
	.mount('#app')