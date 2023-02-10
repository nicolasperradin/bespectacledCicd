<script setup>
import { computed, onBeforeMount, reactive, ref, watch } from 'vue'
import * as yup from 'yup'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import { Form, Field, ErrorMessage } from 'vee-validate'

const $store = useStore()
const $router = useRouter()

const schema = yup.object().shape({
	email: yup
		.string()
		.required('Email is required!')
		.email('Email is invalid!')
		.max(50, 'Must be maximum 50 characters!'),
	password: yup
		.string()
		.required('Password is required!')
		.min(6, 'Must be at least 6 characters!')
		.max(40, 'Must be maximum 40 characters!'),
})

// const valid = true
const message = ref('')
const loading = ref(false)
// const inputs = reactive({ email: '', password: '' })
const errors = reactive({ email: '', password: '' })
const loggedIn = computed(() => $store.state.auth.status.loggedIn)

/* const rules = {
	email: [
		(v) => !!v || 'Email is required',
		(v) => /.+@.+\..+/.test(v) || 'Email must be valid',
		(v) => (v && v.length <= 50) || 'Email must be less than 50 characters'
	], password: [
		(v) => !!v || 'Password is required',
		(v) => (v && v.length >= 6) || 'Password must be at least 6 characters',
		(v) => (v && v.length <= 40) || 'Password must be less than 40 characters'
	], confirmPassword: [
		(v) => !!v || 'Password confirmation is required',
		(v) => (v && v.length >= 6) || 'Password confirmation must be at least 6 characters',
		(v) => (v && v.length <= 40) || 'Password confirmation must be less than 40 characters',
		(v) => (v && v === inputs.password) || 'Password confirmation must match password'
	]
} */

onBeforeMount(() => loggedIn.value && $router.push('/profile'))

watch(message, val => val && setTimeout(() => message.value = '', 3000))
// watch(message, val => Vue.toasted.show.add(val))

const handleLogin = async user => {
	loading.value = true

	try {
		await $store.dispatch('auth/login', user)
		$router.push('/profile')
	} catch (err) {
		message.value = err?.response?.data?.message || err.message || err.toString()

		if (err.response.data.error) errors['email'] = err.response.data.error
		else if (err.response.data.violations) err.response.data.violations.forEach(v => errors[v.propertyPath] = v.message)
	} finally {
		loading.value = false
	}
}
</script>

<template>
	<v-card :loading="loading" class="card card-container mx-auto" max-width="500">
		<Form @submit="handleLogin" :validation-schema="schema">
			<div class="form-group">
				<v-label for="email">Email</v-label>
				<Field name="email" type="email" class="form-control bg-transparent" />
				<ErrorMessage name="email" class="error-feedback text-danger" />
				<span class="text-danger">{{ errors['email'] }}</span>
			</div>

			<div class="form-group">
				<v-label for="password">Password</v-label>
				<Field name="password" type="password" class="form-control bg-transparent" />
				<ErrorMessage name="password" class="error-feedback text-danger" />
				<span class="text-danger">{{ errors['password'] }}</span>
			</div>

			<v-btn type="submit" :loading="loading" :disabled="loading" color="primary">Login</v-btn>
			<v-btn :disabled="loading" color="primary" @click="$router.push('/forgot-password')">Forgot Password?</v-btn>

			<div class="form-group">
				<div v-if="message" class="alert alert-danger" role="alert">
					{{ message }}
				</div>
			</div>
		</Form>
	</v-card>
</template>

<style scoped>
	.card-container.card {
		padding: 40px 40px;
	}

	.card {
		padding: 20px 25px 30px;
		margin: 0 auto 25px;
		margin-top: 50px;
	}
</style>