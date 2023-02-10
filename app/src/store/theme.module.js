export default {
	namespaced: true,
	state: { dark: JSON.parse(localStorage.getItem('dark')) ?? true },
	actions: {},
	mutations: {
		toggle(state) {
			localStorage.setItem('dark', !state.dark)
			state.dark = !state.dark
		}
	}
}