<script setup lang="ts">
import { computed, onBeforeMount, reactive, ref, watch } from 'vue'
import { useVuelidate } from '@vuelidate/core'
import { email, minLength, maxLength, required, sameAs } from '@vuelidate/validators'

import * as yup from 'yup'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store';

const $router = useRouter()
const $store = useAuthStore()

const parallax = new URL('@/assets/carnival.jpeg', import.meta.url).href

const valid = ref(true)
const message = ref('')
const loading = ref(false)
const user = ref($store.user)
const showPassword = ref(false)
const inputs = reactive({ username: '', email: '', password: '', confirmPassword: '' })
const form = ref<null | typeof import('vuetify/components')['VForm']>(null)
const toast = computed(() => { return { state: !!message.value, color: 'success' } })

const errors = reactive({ username: '', email: '', password: '', confirmPassword: '' })

const rules = {
	username: { required, minLength: minLength(3), maxLength: maxLength(20) },
	email: { required, email, maxLength: maxLength(50) },
	password: { required, minLength: minLength(6), maxLength: maxLength(40) },
	confirmPassword: { required, sameAsRawValue: sameAs(inputs.password) }
}

const v$ = useVuelidate(rules, inputs)

// TODO handle this in the router.onBeforeEach hook instead
onBeforeMount(() => user.value && $router.push('/profile'))

watch(message, val => val && setTimeout(() => message.value = '', 3000))
watch(valid, valid => console.log('valid', valid))

const handleRegister = async (user: any) => {
	if (!valid.value) return

	loading.value = true

	try {
		const data = await $store.register(user)
		toast.value.color = 'success'
		message.value = 'Register successful!'
		$router.push('/login')
		$router.go(0)
		// await $store.dispatch('auth/login', user)
	} catch (err: any) {
		toast.value.color = 'danger'
		message.value = err?.response?.data?.message || err.message || err.toString()
	} finally {
		loading.value = false
	}
}
</script>

<template>
	<v-parallax :src="parallax">
		<div class="d-flex flex-column fill-height justify-center align-center">
			<div class="text-white-50 text-h2 font-weight-thin mb-4">BeSpectacled Login</div>
			<div class="text-h4 text-primary">Register</div>
		</div>
	</v-parallax>

	<v-snackbar v-model="toast.state" :timeout="2000" :color="toast.color" elevation="24">{{ message }}</v-snackbar>

	<v-card :disabled="loading || !inputs" :loading="loading || !inputs">
		<v-form ref="form" v-model="valid" @submit.prevent="handleRegister(inputs)">
			<v-card-text>
				<v-row>
					<v-col cols="12" sm="6">
						<v-text-field
							v-model="inputs.username"
							:error-messages="v$.username?.$errors.map((e: any) => e.$message)"
							:counter="20"
							label="Username*"
							required
							@input="v$.username.$touch"
							@blur="v$.username.$touch"
						/>
					</v-col>

					<v-col cols="12" sm="6">
						<v-text-field
							v-model="inputs.email"
							:error-messages="v$.email?.$errors.map((e: any) => e.$message)"
							:counter="50"
							label="Email*"
							type="email"
							required
							@input="v$.email.$touch"
							@blur="v$.email.$touch"
						/>
					</v-col>

					<v-col cols="12" sm="6">
						<v-text-field
							v-model="inputs.password"
							:error-messages="v$.password?.$errors.map((e: any) => e.$message)"
							:counter="40"
							label="Password*"
							:type="showPassword ? 'text' : 'password'"
							required
							@input="v$.password.$touch"
							@blur="v$.password.$touch"
							:append-inner-icon="inputs.password && (showPassword ? 'fa fa-eye-slash' : 'fa fa-eye')"
							@click:append-inner="showPassword = !showPassword"
						/>
					</v-col>

					<v-col cols="12" sm="6">
						<v-text-field
							v-model="inputs.confirmPassword"
							:counter="40"
							label="confirmPassword*"
							type="password"
							required
						/>
					</v-col>
				</v-row>
			</v-card-text>
			
			<v-card-actions>
				<v-btn color="primary" variant="tonal" @click="$router.push('/login')">Already registered?</v-btn>
				<v-spacer />
				<v-btn color="primary" @click="form?.reset()" type="reset">Reset</v-btn>
				<v-btn :disabled="!inputs.username || !inputs.email || !inputs.password || !inputs.confirmPassword" color="primary" variant="elevated" type="submit" @click="v$.$validate">Register</v-btn>
			</v-card-actions>
		</v-form>
	</v-card>
</template>

<style scoped>
.v-parallax {
	height: calc(50vh - (48px + 16px * 2)) !important;
	margin-bottom: 16px;
}

.card-container.card {
	padding: 40px 40px;
}

.card {
	padding: 20px 25px 30px;
	margin: 0 auto 25px;
	margin-top: 50px;
}
</style>