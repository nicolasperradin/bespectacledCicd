import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import { mdi } from 'vuetify/iconsets/mdi'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { aliases, fa } from 'vuetify/iconsets/fa'

export default createVuetify({ components, directives, theme: { dark: true }, icons: {
	defaultSet: 'fa', aliases, sets: { fa, mdi }
}})