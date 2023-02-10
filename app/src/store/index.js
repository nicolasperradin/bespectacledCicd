import { createStore } from 'vuex'

import auth from './auth.module'
import theme from './theme.module'

export default createStore({ modules: { auth, theme } })