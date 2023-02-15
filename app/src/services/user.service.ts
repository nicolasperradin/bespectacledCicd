import axios from 'axios'

import headers from './headers'

const API_URL = import.meta.env.VITE_API_URL

const data = [
	{ id: 1, username: 'root1', email: 'root1@root.com', roles: ['ROLE_ADMIN'] },
	{ id: 2, username: 'root2', email: 'root2@root.com', roles: ['ROLE_USER'] },
	{ id: 3, username: 'Billy Joel', email: 'billyjoel@bespectacled.com', roles: ['ROLE_ARTIST'] },
	{ id: 4, username: 'Taylor Swift', email: 'taylorswift@bespectacled.com', roles: ['ROLE_ARTIST'] },
	{ id: 5, username: 'Madonna', email: 'madonna@bespectacled.com', roles: ['ROLE_ARTIST'] },
	{ id: 6, username: 'Wizkid', email: 'wizkid@bespectacled.com', roles: ['ROLE_ARTIST'] },
	{ id: 6, username: 'Beyonce', email: 'beyonce@bespectacled.com', roles: ['ROLE_ARTIST'] }
]

class UserService {
	all() {
		return Promise.resolve({ data })
		// return axios.get(API_URL + 'users', { headers: headers() })
	}

	artists() {
		return Promise.resolve({ data: data.filter(u => u.roles.includes('ROLE_ARTIST')) })
		// return axios.get(API_URL + 'users', { headers: headers() })
	}

	async profile() {
		// return Promise.resolve({ data: JSON.parse(localStorage.getItem('user') || 'null') })
		return axios.get(API_URL + '/users/me', { headers: headers() })
			.then(({ data }) => localStorage.setItem('me', JSON.stringify(data)))
	}

	updateProfile(data: any) {
		return Promise.resolve({ data: { ...JSON.parse(localStorage.getItem('user') || '{}'), ...data } }).then(response => localStorage.setItem('user', JSON.stringify(response.data)))
		// return axios.put(API_URL + 'users/me', data, { headers: headers() })
	}

	resetPassword(data: any) {
		return axios.post(API_URL + 'users/reset-password', data, { headers: headers() })
	}

	confirm(data: any) {
		return axios.post(API_URL + 'users/confirm', data, { headers: headers() })
	}
}

export default new UserService()