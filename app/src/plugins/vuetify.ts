import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import { mdi } from 'vuetify/iconsets/mdi'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { aliases, fa } from 'vuetify/iconsets/fa'
import { VDataTableServer } from 'vuetify/labs/VDataTable'


const ScrollTo = (el: HTMLElement, { value }: any) => el.addEventListener('click', () => document && document.querySelector(value).scrollIntoView({ behavior: 'smooth' }))
const Notify = (el: HTMLElement, { value }: any) => el.addEventListener('click', () => Notification.requestPermission().then(() => new Notification('BeSpectacled', { body: (value[0]?.name || value[0]?.title) + value[1], icon: value[0].src })))
// TODO add other directives like v-admin

export default createVuetify({
	components: { ...components, VDataTableServer },
	directives: { ...directives, ScrollTo, Notify },
	theme: { defaultTheme: 'dark' },
	icons: { defaultSet: 'fa', aliases, sets: { fa, mdi } }
})