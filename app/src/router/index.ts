import { createRouter, createWebHistory } from 'vue-router'

import roomRoutes from './room'
import userRoutes from './user'
import eventRoutes from './event'
import ticketingRoutes from './ticketing'
import roomreservationRoutes from './roomreservation'
import userconfirmationRoutes from './userconfirmation'
import paymenttransactionRoutes from './paymenttransaction'

const routes = [
	{
		path: '/admin/', component: () => import('@/layouts/Admin.vue'), children: [
			{ path: '', name: 'admin', component: () => import('@/views/admin/HomePage.vue') },
			...roomRoutes,
			...userRoutes,
			...eventRoutes,
			...ticketingRoutes,
			...roomreservationRoutes,
			...userconfirmationRoutes,
			...paymenttransactionRoutes
		]
	},
	{
		path: '/', component: () => import('@/layouts/Default.vue'), children: [
			{ path: '', name: 'home', component: () => import('@/views/default/HomePage.vue') },
			{ path: 'artists', name: 'artists', component: () => import('@/views/default/ArtistsPage.vue') },
			{ path: 'artists/:id', name: 'artist', component: () => import('@/views/default/BlankPage.vue') },
			{ path: 'events', name: 'events', component: () => import('@/views/default/EventsPage.vue') },
			{ path: 'events/:id', name: 'event', component: () => import('@/views/default/BlankPage.vue') },
			{ path: 'rooms', name: 'rooms', component: () => import('@/views/default/VenuesPage.vue') },
			{ path: 'venues/:id', name: 'room', component: () => import('@/views/default/BlankPage.vue') },
			{ path: 'schedule', name: 'schedule', component: () => import('@/views/default/SchedulePage.vue') },
			{ path: 'ticketing', name: 'ticketing', component: () => import('@/views/default/BlankPage.vue') },

			{ path: 'orders', name: 'orders', component: () => import('@/views/default/BlankPage.vue') },
			{ path: 'tickets', name: 'tickets', component: () => import('@/views/default/BlankPage.vue') },
			{ path: 'profile', name: 'profile', component: () => import('@/views/default/ProfilePage.vue') },

			{ path: 'login', name: 'login', component: () => import('@/views/default/LoginPage.vue') },
			{ path: 'register', name: 'register', component: () => import('@/views/default/RegisterPage.vue') },
			{ path: 'forgot-password', name: 'forgot-password', component: () => import('@/views/default/ForgotPasswordPage.vue') },

			{ path: '/:pathMatch(.*)*', name: 'not-found', component: () => import('@/views/default/NotFoundPage.vue') }
		]
	}
]

const router = createRouter({ history: createWebHistory(), routes })

export default router

router.beforeEach((to, from, next) => {
	const guestPages = [
		'/', '/artists', '/events', '/rooms', '/schedule',
		'/login', '/register', '/forget-password'
	]
	const authRequired = !guestPages.includes(to.path)
	const user = JSON.parse(localStorage.getItem('user') || 'null')

	// trying to access a restricted page + not logged in
	// redirect to login page
	if (authRequired && !user?.token) next('/login')
	else next()
})