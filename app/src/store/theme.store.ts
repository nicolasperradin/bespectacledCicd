import { defineStore } from 'pinia'

export const useThemeStore = defineStore({
	id: 'theme',
	state: () => ({ dark: JSON.parse(localStorage.getItem('dark') || 'true') }),
	actions: {
		toggle() {
			localStorage.setItem('dark', JSON.stringify(!this.dark))
			this.dark = !this.dark
		}
	}
})