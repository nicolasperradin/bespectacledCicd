import axios from 'axios'
import { defineStore } from 'pinia'

import router from '@/router'
import headers from './headers'
import { SubmissionErrors } from '@/types/error'
import { SubmissionError } from '@/utils/error'
import { User } from '@/types/user'
import api from '@/utils/api'

interface State {
	user?: any
	isLoading: boolean
	error?: string
	violations?: SubmissionErrors
}

const baseUrl = import.meta.env.VITE_API_URL

export const useAuthStore = defineStore('auth', {
	state: (): State => ({
		// initialize state from local storage to enable user to stay logged in
		user: JSON.parse(localStorage.getItem('user') || 'null'),
		isLoading: false,
		error: undefined,
		violations: undefined
	}),
	actions: {
		async login(user: any) {
			this.error = undefined
			this.violations = undefined
			this.isLoading = true

			return axios.post(`${baseUrl}/login`, user)
				.then(async ({ data }) => {
					if (data.token) {
						// fetch user profile
						const profile = await this.profile(data)

						// update pinia state and keep user logged in between page refreshes
						this.setUser({ ...data, ...profile.data })
					} else throw new Error('Missing token.')
				})
				.catch(error => {
					// console.log(Object.keys(error))
					// if (error?.response?.data?.message) {
					// 	this.error = error.response.data.message
					// 	return
					// }
					if (error instanceof SubmissionError) {
						this.violations = error.errors
						this.error = error.errors._error
						throw new Error(this.error)
					}

					this.error = this.error = error?.response?.data?.message || error.message || error.toString()
					throw new Error(this.error)
				})
				.finally(() => {
					this.isLoading = false
				})
		},
		async register(payload: any) {
			this.setError(undefined)
			this.setViolations(undefined)
			this.toggleLoading()

			try {
				const response = await api('users', {
					method: 'POST',
					body: JSON.stringify(payload)
				})
				const data: User = await response.json()

				this.toggleLoading()
				this.setUser(data)
			} catch (error) {
				this.toggleLoading()

				if (error instanceof SubmissionError) {
					this.setViolations(error.errors)
					this.setError(error.errors._error)
					return
				}

				if (error instanceof Error) this.setError(error.message)
			}
		},
		logout() {
			this.user = null
			localStorage.removeItem('user')
			router.push('/login')
			// router.go(0)
		},
		profile(data = null) {
			return axios.get(`${baseUrl}/users/me`, { headers: headers(data) })
		},
		async forgotPassword(user: any) {
			return axios.post(`${baseUrl}/forgot-password`, user)
				.then(response => { return response.data })
		},
		setUser(user: User) {
			this.user = user
			localStorage.setItem('user', JSON.stringify(user))
		},
		toggleLoading() {
			this.isLoading = !this.isLoading
		},
		setError(error: string | undefined) {
			this.error = error
			setTimeout(() => { this.error = undefined }, 5000)
		},
		setViolations(violations: SubmissionErrors | undefined) {
			this.violations = violations
			setTimeout(() => { this.violations = undefined }, 5000)
		}
	}
})