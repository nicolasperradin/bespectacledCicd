<script setup>
import { computed, onBeforeMount, reactive, ref, watch } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

const $store = useStore()
const $router = useRouter()

const step = ref(1)
const form = ref(null)
const valid = ref(true)
const message = ref('')
const loading = ref(false)
const successful = ref(false)
const loggedIn = computed(() => $store.state.auth.status.loggedIn)
const inputs = reactive({ username: '', email: '', password: '', confirmPassword: '' })
// TODO errors variable is useless
const errors = reactive({ username: '', email: '', password: '', confirmPassword: '' })
const currentTitle = computed(() => { return ['Basic info', 'Create a password'][step.value - 1] || 'Account created' })

const rules = {
	username: [
		v => !!v || 'Username is required',
		v => (v && v.length >= 3) || 'Username must be at least 3 characters',
		v => (v && v.length <= 20) || 'Username must be less than 20 characters'
	], email: [
		v => !!v || 'Email is required',
		v => /.+@.+\..+/.test(v) || 'Email must be valid',
		v => (v && v.length <= 50) || 'Email must be less than 50 characters'
	], password: [
		v => !!v || 'Password is required',
		v => (v && v.length >= 6) || 'Password must be at least 6 characters',
		v => (v && v.length <= 40) || 'Password must be less than 40 characters'
	], confirmPassword: [
		v => !!v || 'Password confirmation is required',
		v => (v && v.length >= 6) || 'Password confirmation must be at least 6 characters',
		v => (v && v.length <= 40) || 'Password confirmation must be less than 40 characters',
		v => (v && v === inputs.password) || 'Password confirmation must match password'
	]
}

onBeforeMount(() => loggedIn.value && $router.push('/profile'))

watch(valid, val => val === null && (valid.value = true))
watch(errors, val => console.log('errors', val))
watch(message, val => val && setTimeout(() => message.value = '', 3000))

const handleRegister = async user => {
	message.value = ''
	successful.value = false
	loading.value = true

	try {
		const { valid } = await form.value.validate()
		if (!valid) throw new Error('You must fill out all fields correctly.')
		await $store.dispatch('auth/register', user)
		step.value = 3
		message.value = 'You have registered. You can now login.'
		successful.value = true
	} catch (err) {
		message.value = err?.response?.data?.message || err.message || err.toString()
		successful.value = false
		// step.value = 1

		if (err?.response?.data?.violations) err.response.data.violations.forEach(v => errors[v.propertyPath] = v.message)
	} finally {
		loading.value = false
	}
}
</script>

<template>
	<!-- TODO improve alert -->
	<v-alert v-show="message" density="compact" :type="successful ? 'success' : 'error'" class="position-absolute me-4" border @click="message = ''">
		{{ message }}
	</v-alert>

	<!-- color="deep-purple-accent-4" -->
	<!-- <v-snackbar :timeout="3000" elevation="24" :color="successful ? 'success' : 'error'">
		<template v-slot:activator="{ props }">
			<v-btn color="deep-purple-accent-4" class="ma-2" v-bind="props">open</v-btn>
		</template>

		{{ message }}
    </v-snackbar> -->

	<div class="text-h3 text-center my-10">Register</div>

	<v-form ref="form" v-model="valid" @submit.prevent="handleRegister(inputs)">
		<v-card :loading="loading" class="mx-auto" max-width="500">
			<v-card-title class="d-flex text-h6 font-weight-regular align-center justify-space-between">
				<span>{{ currentTitle }}</span>

				<v-item-group v-model="step" class="text-center" mandatory>
					<v-item v-for="n in 3" :key="`btn-${n}`" v-slot="{ isSelected }" :value="n">
						<v-btn density="comfortable" :variant="isSelected ? 'outlined' : 'text'" :icon="`fa-${n}`" />
					</v-item>
				</v-item-group>
			</v-card-title>

			<v-window v-model="step">
				<v-window-item :value="1">
					<v-card-text>
						<span class="text-caption text-grey-darken-1">This is the username we will use to address you.</span>
						<v-text-field label="Username" type="text" name="username" placeholder="John Doe" required :counter="20" :rules="rules.username" v-model="inputs.username" />

						<span class="text-caption text-grey-darken-1">This is the email you will use to login to your BeSpectacled account.</span>
						<v-text-field label="Email" type="email" name="email" placeholder="john@doe.com" required :counter="50" :rules="rules.email" v-model="inputs.email" />
					</v-card-text>
				</v-window-item>

				<v-window-item :value="2">
					<v-card-text>
						<span class="text-caption text-grey-darken-1">Please enter a password for your account.</span>
						<v-text-field label="Password" type="password" name="password" required :counter="40" :rules="rules.password" v-model="inputs.password" />

						<span class="text-caption text-grey-darken-1">Please confirm your password.</span>
						<v-text-field label="Confirm Password" type="password" name="confirmPassword" required :counter="40" :rules="rules.confirmPassword" />
					</v-card-text>
				</v-window-item>

				<v-window-item :value="3">
					<div class="pa-4 text-center">
						<v-img class="mb-4" contain height="128" src="https://cdn.vuetifyjs.com/images/logos/v.svg"></v-img>
						<h3 class="text-h6 font-weight-light mb-2">Welcome to BeSpectacled</h3>
						<span class="text-caption text-grey">Thanks for signing up!</span>
					</div>
				</v-window-item>
			</v-window>

			<v-btn v-if="!successful" variant="text" @click="$router.push('/login')">Already registered?</v-btn>
			
			<v-divider></v-divider>

			<v-card-actions>
				<v-btn v-if="step === 2" variant="text" :disabled="loading" @click="step--; form.resetValidation(); valid = true">Back</v-btn>
				<v-spacer></v-spacer>
				<v-btn v-if="step === 1" color="primary" variant="flat" :disabled="loading || !valid" @click="step++">Next</v-btn>
				<v-btn v-if="step === 2" type="submit" color="primary" variant="flat" :disabled="loading || !valid" :loading="loading">Register</v-btn>
				<v-btn v-if="step === 3" color="primary" variant="flat" :disabled="loading" @click="$router.push('/login')">Login</v-btn>
			</v-card-actions>
		</v-card>
	</v-form>
</template>

<style scoped>
	.v-alert {
		right: 0;
	}
</style>