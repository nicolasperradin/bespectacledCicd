<script setup lang="ts">
import { ref, Ref, toRef } from 'vue'
import { VForm } from 'vuetify/components'

import type { Item } from '@/types/item'
import type { Event } from '@/types/event'
import type { SubmissionErrors } from '@/types/error'
import FormRepeater from '@/components/common/FormRepeater.vue'

const props = defineProps<{
	values?: Event
	errors?: SubmissionErrors
}>()

const violations = toRef(props, 'errors')

const item: Ref<Event> = ref({})

if (props.values) item.value = { ...props.values }

const emit = defineEmits<{ (e: 'submit', item: Event): void }>()

// TODO example to use for ref declarations
const form: Ref<VForm | null> = ref(null)

function resetForm() {
	if (!form.value) return
	form.value.reset()
}

// TODO use vue-validate and use assertions in Entities
</script>

<template>
	<v-form ref="form" @submit.prevent="emit('submit', item)">
		<v-row>
			<v-col cols="12" sm="6" md="6">
				<v-text-field
					v-model="item.title"
					:error="Boolean(violations?.title)"
					:error-messages="violations?.title"
					:label="$t('event.title')"
					required
					prepend-icon="fa fa-font-case"
					append-inner-icon="fa fa-trash"
					@click:append-inner="item.title = undefined"
				/>
			</v-col>

			<v-col cols="12" sm="6" md="6">
				<!-- TODO use v-select instead -->
				<v-text-field
					v-model="item.type"
					:error="Boolean(violations?.type)"
					:error-messages="violations?.type"
					:label="$t('event.type')"
					required
					prepend-icon="fa fa-folder"
					append-inner-icon="fa fa-trash"
					@click:append-inner="item.type = undefined"
				/>
			</v-col>

			<v-col cols="12" sm="6" md="6">
				<!-- TODO use modifiers like here when possible -->
				<v-text-field
					v-model.number="item.price"
					:error="Boolean(violations?.price)"
					:error-messages="violations?.price"
					:label="$t('event.price')"
					required
					prepend-icon="fa fa-dollar-sign"
					append-inner-icon="fa fa-trash"
					@click:append-inner="item.price = undefined"
				/>
			</v-col>

			<v-col cols="12" sm="6" md="6">
				<!-- TODO use v-file-input instead -->
				<v-text-field
					v-model="item.src"
					:error="Boolean(violations?.src)"
					:error-messages="violations?.src"
					:label="$t('event.src')"
					required
					prepend-icon="fa fa-image"
					append-inner-icon="fa fa-trash"
					@click:append-inner="item.src = undefined"
				/>
			</v-col>

			<v-col cols="12" sm="6" md="6">
				<!-- TODO use v-autocomplete instead -->
				<v-text-field
					v-model="item.venue"
					:error="Boolean(violations?.venue)"
					:error-messages="violations?.venue"
					:label="$t('event.venue')"
					required
					append-inner-icon="fa fa-trash"
					@click:append-inner="item.venue = undefined"
				/>
			</v-col>

			<v-col cols="12">
				<!-- TODO use v-autocomplete in the FormRepeater instead -->
				<FormRepeater :values="item.artists" :label="$t('event.artists')" @update="(values: any) => item.artists = values" />
			</v-col>

			<!-- TODO sme as artists? -->
			<v-col cols="12" sm="6" md="6">
				<v-text-field
					v-model="item.schedules"
					:error="Boolean(violations?.schedules)"
					:error-messages="violations?.schedules"
					:label="$t('event.schedules')"
					append-inner-icon="fa fa-trash"
					@click:append-inner="item.schedules = undefined"
				/>
			</v-col>
		</v-row>

		<v-row>
			<v-col cols="12" sm="6" md="6">
				<v-btn color="primary" type="submit">{{ $t("submit") }}</v-btn>
				<v-btn color="primary" variant="text" class="ml-2" v-text="$t('reset')" @click="form?.reset()" />
			</v-col>
		</v-row>
	</v-form>
</template>