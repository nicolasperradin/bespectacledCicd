import { createWebHistory, createRouter } from 'vue-router'

import Home from './components/HomePage.vue'
import Blank from './components/BlankPage.vue'
import Login from './components/LoginPage.vue'
import Events from './components/EventsPage.vue'
import Venues from './components/VenuesPage.vue'
import Artists from './components/ArtistsPage.vue'
import Profile from './components/ProfilePage.vue'
import NotFound from './components/NotFoundPage.vue'
import Register from './components/RegisterPage.vue'
import Schedule from './components/SchedulePage.vue'
import ForgotPassword from './components/ForgotPasswordPage.vue'

const routes = [
	{ path: '/', name: 'home', component: Home },
	{ path: '/news', name: 'news', component: Blank },
	{ path: '/news/:id', name: 'news', component: Blank },
	{ path: '/artists', name: 'artists', component: Artists },
	{ path: '/artists/:id', name: 'artist', component: Blank },
	{ path: '/events', name: 'events', component: Events },
	{ path: '/events/:id', name: 'event', component: Blank },
	{ path: '/venues', name: 'venues', component: Venues },
	{ path: '/venues/:id', name: 'venue', component: Blank },
	{ path: '/schedule', name: 'schedule', component: Schedule },
	{ path: '/ticketing', name: 'ticketing', component: Blank },

	{ path: '/orders', name: 'orders', component: Blank },
	{ path: '/tickets', name: 'tickets', component: Blank },
	{ path: '/profile', name: 'profile', component: Profile },

	{ path: '/login', name: 'login', component: Login },
	{ path: '/register', name: 'register', component: Register },
	{ path: '/forgot-password', name: 'forgot-password', component: ForgotPassword },

	{ path: '/:pathMatch(.*)*', name: 'not-found', component: NotFound }
]

const router = createRouter({ history: createWebHistory(), routes })

export default router

router.beforeEach((to, from, next) => {
	const guestPages = [
		'/', '/news', '/artists', '/events', '/venues', '/schedule',
		'/login', '/register', '/forget-password'
	]
	const authRequired = !guestPages.includes(to.path)
	const loggedIn = localStorage.getItem('user')

	// trying to access a restricted page + not logged in
	// redirect to login page
	if (authRequired && !loggedIn) next('/login')
	else next()
})