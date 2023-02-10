import axios from 'axios'

const API_URL = 'http://localhost/api'

class AuthService {
	login({ email, password }) {
		return axios
			.post(API_URL + '/login', { email, password })
			.then(response => {
				if (response.data.token) localStorage.setItem('user', JSON.stringify(response.data))
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