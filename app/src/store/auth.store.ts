import axios from 'axios'
import { defineStore } from 'pinia'

import router from '@/router'
import headers from './headers'

const baseUrl = import.meta.env.VITE_API_URL

export const useAuthStore = defineStore({
	id: 'auth',
	state: () => ({
		// initialize state from local storage to enable user to stay logged in
		user: JSON.parse(localStorage.getItem('user') || 'null'),
		returnUrl: '/profile'
	}),
	actions: {
		async login(user: any) {
			return axios.post(`${baseUrl}/login`, user)
				.then(async ({ data }) => {
					if (data.token) {
						const profile = await this.profile(data)

						// update pinia state
						this.user = { ...data, ...profile.data }

						// store user details and jwt in local storage to keep user logged in between page refreshes
						localStorage.setItem('user', JSON.stringify(this.user))

						// redirect to previous url or default to home page
						// router.push(this.returnUrl || '/')
						return this.returnUrl || '/'
					} else throw new Error('Missing token.')
				})
		},
		register(user: any) {
			return axios.post(`${baseUrl}/users`, user)
		},
		logout() {
			this.user = null
			localStorage.removeItem('user')
			router.push('/login')
			router.go(0)
		},
		profile(data = null) {
			return axios.get(`${baseUrl}/users/me`, { headers: headers(data) })
		},
		async forgotPassword(user: any) {
			return axios.post(`${baseUrl}/forgot-password`, user)
				.then(response => { return response.data })
		}
	}
})