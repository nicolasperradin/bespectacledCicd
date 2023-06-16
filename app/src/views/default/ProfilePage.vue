<script setup lang="ts">
import { onMounted, ref, watch } from 'vue'
import { useVuelidate } from '@vuelidate/core'
import { email, minLength, maxLength, required, sameAs } from '@vuelidate/validators'

import { useAuthStore } from '@/store'

const $store = useAuthStore()

const parallax = new URL('@/assets/maestro.jpeg', import.meta.url).href

const valid = ref(true)
const user = ref($store.user)
const form = ref<null | typeof import('vuetify/components')['VForm']>(null)
const dialog = ref({ state: false, form: null, password: '', confirmPassword: '' })

const rules = {
	username: { required, minLength: minLength(3), maxLength: maxLength(20) },
	email: { required, email, maxLength: maxLength(50) },
	password: { required, minLength: minLength(6), maxLength: maxLength(40) },
	confirmPassword: { required, sameAsPassword: sameAs(dialog.value.password) }
}

const v$ = useVuelidate(rules, user)

onMounted(async () => {
	const { data } = await $store.profile()
	user.value = { ...user.value, ...data }
})

watch(dialog, dialog => console.log('dialog', dialog))
</script>

<template>
	<v-parallax :src="parallax">
		<div class="d-flex flex-column fill-height justify-center align-center">
			<div class="text-white-50 text-h2 font-weight-thin mb-4">{{ user.username && `${user.username}'s ` }}Profile</div>
			<div class="text-h4 text-secondary">Edit your profile information</div>
		</div>
	</v-parallax>

	<v-card :disabled="!user.id" :loading="!user.id">
		<v-card-text>
			<v-form ref="form" v-model="valid" @submit.prevent="">
				<v-row>
					<!-- <v-col cols="12" sm="6">
						<v-text-field v-model="user.firstName" label="First Name*" required />
					</v-col>

					<v-col cols="12" sm="6">
						<v-text-field v-model="user.lastName" label="Last Name*" required />
					</v-col> -->

					<!-- <v-col cols="12">
						<v-text-field v-model="user.token" label="Token*" />
					</v-col> -->

					<v-col cols="12" sm="6">
						<v-text-field
							v-model="user.username"
							:error-messages="v$.username?.$errors.map((e: any) => e.$message)"
							:counter="10"
							label="Username*"
							required
							@input="v$.username.$touch"
							@blur="v$.username.$touch"
						/>
					</v-col>

					<v-col cols="12" sm="6">
						<v-text-field
							v-model="user.email"
							:error-messages="v$.email?.$errors.map((e: any) => e.$message)"
							:counter="10"
							label="Email*"
							required
							@input="v$.email.$touch"
							@blur="v$.email.$touch"
						/>
					</v-col>

					<v-col cols="12">
						<v-autocomplete
							v-model="user.roles"
							:items="['ROLE_USER', 'ROLE_ADMIN', 'ROLE_ARTIST']"
							label="Roles"
							multiple
							chips
							closable-chips
						/>
					</v-col>
				</v-row>
			</v-form>
		</v-card-text>

		<v-card-actions>
			<v-dialog v-model="dialog.state" :scrim="false" persistent>
      			<template #activator="{ props }">
					<v-btn color="red" v-bind="props">Reset Password</v-btn>
				</template>

				<v-card :disabled="!user.id" :loading="!user.id">
					<v-card-text>
						<v-form ref="dialog.form">
							<v-row>
								<v-col cols="12" sm="6">
									<v-text-field
										v-model="dialog.password"
										:error-messages="v$.password.$errors.map((e: any) => e.$message)"
										:counter="10"
										label="Current Password*"
										required
										@input="v$.password.$touch"
										@blur="v$.password.$touch"
									/>
								</v-col>

								<v-col cols="12" sm="6">
									<v-text-field
										v-model="dialog.confirmPassword"
										:error-messages="v$.confirmPassword.$errors.map((e: any) => e.$message)"
										:counter="10"
										label="New Password*"
										required
										@input="v$.confirmPassword.$touch"
										@blur="v$.confirmPassword.$touch"
									/>
								</v-col>
							</v-row>
						</v-form>
					</v-card-text>

					<v-card-actions>
						<v-spacer></v-spacer>
						<v-btn color="primary" @click="dialog.state = false">Cancel</v-btn>
						<v-btn color="primary" variant="elevated" @click="">Submit</v-btn>
					</v-card-actions>
				</v-card>
			</v-dialog>

			<v-spacer></v-spacer>
			<v-btn color="primary" @click="form?.reset()">Reset</v-btn>
			<v-btn color="primary" variant="elevated" @click="">Save</v-btn>
		</v-card-actions>
	</v-card>
</template>

<style scoped>
.v-parallax {
	height: calc(50vh - (48px + 16px * 2)) !important;
	margin-bottom: 16px;
}
</style>