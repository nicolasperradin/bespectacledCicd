export default {
	namespaced: true,
	state: { dark: JSON.parse(localStorage.getItem('dark') || 'true') },
	actions: {},
	mutations: {
		toggle(state: any) {
			localStorage.setItem('dark', JSON.stringify(!state.dark))
			state.dark = !state.dark
		}
	}
}