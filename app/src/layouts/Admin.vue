<script setup>
import { computed, onBeforeMount, onMounted, ref } from 'vue'
import { useTheme } from 'vuetify'
import { useRouter } from 'vue-router'
import { useAuthStore, useThemeStore } from '@/store'
import { useEventListStore } from '@/store/event/list'

import UserService from '@/services/user.service'
import EventService from '@/services/event.service'
import VenueService from '@/services/venue.service'

const $theme = useTheme()
const $router = useRouter()
const $store = useAuthStore()
const $themeStore = useThemeStore()
const $eventListStore = useEventListStore()

const search = ref('')
const drawer = ref(false)
const user = computed(() => $store.user)

const categories = ref([
	{ name: 'Users', icon: 'fa fa-user-tie', to: '/users/', key: 'username', children: [] },
	{ name: 'Events', icon: 'fa fa-star', to: '/events/', key: 'title', children: [] },
	{ name: 'Venues', icon: 'fa fa-location-dot', to: '/rooms/', key: 'name', children: [] },
	{ name: 'Schedules', icon: 'fa fa-calendar-days', to: '/schedule/' }
])

// onBeforeMount(() => $theme.global.name.value = $store.state.theme.dark ? 'dark' : 'light')
onBeforeMount(() => $theme.global.name.value = $themeStore.dark ? 'dark' : 'light')

onMounted(async () => {
	const { data: users } = await UserService.all()
	categories.value.find(c => c.name === 'Artists').children = users

	// const { data: events } = await $eventListStore.getItems()
	const { data: events } = await EventService.all()
	categories.value.find(c => c.name === 'Events').children = events

	const { data: venues } = await VenueService.all()
	categories.value.find(c => c.name === 'Venues').children = venues
	console.log(events)
})

const filteredCategories = computed(() => {
	return categories.value.filter(c => c?.children?.length > 0).map(c => {
		return { ...c, children: c.children.filter(child => child[c.key].toLowerCase().includes(search.value.toLowerCase())) }
	})
})

const toggle = () => {
	$themeStore.toggle()
	// $theme.global.name.value = $store.state.theme.dark ? 'dark' : 'light'
	$theme.global.name.value = $themeStore.dark ? 'dark' : 'light'
}

const logout = () => $store.logout()

const resendVerificationEmail = () => {
	// TODO $store.dispatch('auth/resendVerificationEmail')
	alert('This feature has not been implemented yet.')
}
</script>

<template>
	<v-app dark>
		<v-app-bar color="pink-accent-4" density="compact" app>
			<template v-slot:prepend>
				<v-app-bar-nav-icon @click.stop="drawer = !drawer" />
				<v-btn prepend-icon="fa fa-glasses" color="white" @click="$router.push('/admin')">BeSpectacled Admin</v-btn>

				<v-dialog scrollable>
					<template v-slot:activator="{ props }">
						<v-btn prepend-icon="fa fa-search" v-bind="props">Search</v-btn>
					</template>

					<template v-slot:default="{ isActive }">
						<v-card>
							<v-toolbar color="primary" title="Search BeSpectacled">
								<v-btn icon="fa fa-times" @click="isActive.value = false" />
							</v-toolbar>

							<v-text-field
								v-model.trim="search"
								prepend-inner-icon="fa fa-search"
								density="comfortable"
								label="Search for..."
								name="search"
								placeholder="e.g. artists, events, etc..."
								hide-details
								:autofocus="isActive.value"
							/>

							<v-card-text>
								<v-list v-if="search" lines="one" density="compact">
									<template v-for="{ name, icon, to, key, children } in filteredCategories" :key="name">
										<div class="text-high-emphasis font-weight-black text-uppercase" @click="$router.push('/admin' + to)">Manage {{ name }}</div>
										<v-list-item :prepend-icon="icon" v-for="child in children" :key="child" :title="child[key]" @click="$router.push('/admin' + to + child.id); isActive.value = false" />
									</template>
								</v-list>

								<v-container v-else class="text-center">
									<v-icon size="100">fa fa-info-circle</v-icon>
									<v-list-subheader class="d-inline ">Search for artists, events, venues, etc...</v-list-subheader>
								</v-container>
							</v-card-text>
						</v-card>
					</template>
				</v-dialog>
			</template>

			<v-spacer></v-spacer>

			<v-btn v-if="user" prepend-icon="fa fa-arrow-right" variant="outlined" @click="$router.push('/')">Return to Main Site</v-btn>
			<v-btn v-else prepend-icon="fa fa-right-to-bracket" @click="$router.push('/login')">Login</v-btn>
			<v-btn :icon="$theme.global.current.value.dark ? 'fa fa-sun' : 'fa fa-moon'" @click="toggle" />
		</v-app-bar>

		<v-navigation-drawer v-model="drawer" expand-on-hover rail>
			<v-list v-if="user">
				<v-list-item prepend-avatar="https://cdn.vuetifyjs.com/images/john.jpg"
					:title="user?.username || 'John Doe'"
					:subtitle="user?.email || 'john@doe.com'"
				>
					<template v-slot:append>
						<v-btn variant="text" icon="fa fa-pen" @click="$router.push('/profile')" />
					</template>
				</v-list-item>

				<v-list-item prepend-icon="fa fa-id-card" title="Profile" @click="$router.push('/profile')" />
				<v-list-item prepend-icon="fa fa-sign-out-alt" title="Logout" @click.prevent="logout" />
			</v-list>

			<v-list v-else>
				<v-list-item prepend-icon="fa fa-sign-in-alt" title="Login" @click="$router.push('/login')" />
				<v-list-item prepend-icon="fa fa-user-plus" title="Register" @click="$router.push('/register')" />
			</v-list>

			<v-divider />

			<v-list density="compact" nav>
				<v-list-item v-for="{ name, icon, to } in categories" :key="name" :prepend-icon="icon" append-icon="fa fa-list" :title="`Manage ${name}`" @click="$router.push('/admin' + to)" />
			</v-list>

			<v-list density="compact" nav>
				<v-list-item v-for="{ name, icon, to } in categories" :key="name" :prepend-icon="icon" append-icon="fa fa-plus-circle" :title="`Create ${name.slice(0, -1)}`" @click="$router.push('/admin' + to + 'create')" />
			</v-list>
		</v-navigation-drawer>

		<v-main>
			<v-banner v-if="user && user?.status === 0" class="align-center" lines="one" icon="$info" color="info" sticky>
				<v-banner-text>
					You need to verify your email address before you can continue.
				</v-banner-text>

				<template v-slot:actions>
					<v-btn @click="resendVerificationEmail">Resend verification email</v-btn>
				</template>
			</v-banner>

			<v-container>
				<router-view />
			</v-container>
		</v-main>
	</v-app>
</template>

<style>
.v-toolbar {
	transition: all .2s ease;
}

.v-banner {
	z-index: 2;
	top: 3.4em !important;
}

.v-overlay__content {
	max-width: 70vw !important;
}

.v-list-item + :not(.v-list-item) {
	margin-top: 1em;
}

.v-divider {
	opacity: 1 !important;
	width: 100%;
}
</style>