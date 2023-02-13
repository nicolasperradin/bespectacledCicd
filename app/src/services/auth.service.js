import axios from 'axios'

const API_URL = 'http://localhost/api'

const data = { id: 1, username: 'root1', email: 'root1@root.com', roles: ['ROLE_ADMIN'], token: Math.random().toString(36).substring(7) }

class AuthService {
	login({ email, password }) {
		return Promise.resolve({ data: { ...data, email, password } })
		// return axios
		// 	.post(API_URL + '/login', { email, password })
			.then(response => {
				if (response.data.token) localStorage.setItem('user', JSON.stringify(response.data))
				else throw new Error('Missing token.')
				return response.data
			})
	}
	register({ username, email, password }) {
		return axios.post(API_URL + '/users', { username, email, password })
	}
	logout() {
		localStorage.removeItem('user')
	}
	forgotPassword({ email }) {
		return axios
			.post(API_URL + '/forgot-password', { email })
			.then(response => { return response.data })
	}
}

export default new AuthService()