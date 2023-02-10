import { createApp } from 'vue'

import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'

import App from './App.vue'
import store from './store'
import router from './router'
import vuetify from './plugins/vuetify'
import { FontAwesomeIcon } from './plugins/font-awesome'

createApp(App)
	.use(store)
	.use(router)
	.use(vuetify)
	.component('font-awesome-icon', FontAwesomeIcon)
	.directive('scroll-to', (el, binding) => {
		el.addEventListener('click', () => document.querySelector(binding.value).scrollIntoView({ behavior: 'smooth' }))
	})
	.mount('#app')