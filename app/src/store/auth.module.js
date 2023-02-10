import AuthService from '../services/auth.service'

const user = JSON.parse(localStorage.getItem('user'))
const state = user
	? { status: { loggedIn: true }, user }
	: { status: { loggedIn: false }, user: null }

// const state = { currentUser: null, isAuthenticating: false, token: null }

export default {
	namespaced: true,
	state,
	actions: {
		login({ commit }, user) {
			return AuthService.login(user).then(
				user => {
					commit('loginSuccess', user)
					return Promise.resolve(user)
				},
				error => {
					commit('loginFailure')
					return Promise.reject(error)
				}
			)
		},
		register({ commit }, user) {
			return AuthService.register(user).then(
				response => {
					commit('registerSuccess')
					return Promise.resolve(response.data)
				},
				error => {
					commit('registerFailure')
					return Promise.reject(error)
				}
			)
		},
		logout({ commit }) {
			AuthService.logout()
			commit('logout')
		},
		forgotPassword({ commit }, user) {
			return AuthService.forgotPassword(user).then(
				user => {
					commit('forgotPasswordSuccess', user)
					return Promise.resolve(user)
				},
				error => {
					commit('forgotPasswordFailure')
					return Promise.reject(error)
				}
			)
		},
	},
	mutations: {
		loginSuccess(state, user) {
			state.status.loggedIn = true
			state.user = user
		},
		loginFailure(state) {
			state.status.loggedIn = false
			state.user = null
		},
		logout(state) {
			state.status.loggedIn = false
			state.user = null
		},
		registerSuccess(state) {
			state.status.loggedIn = false
		},
		registerFailure(state) {
			state.status.loggedIn = false
		},
		forgotPasswordSuccess(state, user) {
			state.status.loggedIn = false
			state.user = user
		}
	}
}