<script setup lang="ts">
import { toRef } from 'vue'

const props = defineProps<{ show: boolean }>()

// TODO transform to const
let show = toRef(props, 'show')

const emit = defineEmits<{
	(e: 'cancel'): void
	(e: 'delete'): void
}>()
</script>

<template>
	<v-dialog v-model="show" persistent width="300">
		<v-card>
			<v-card-text v-text="$t('confirmDelete')" />

			<v-card-actions>
				<v-spacer />
				<v-btn color="error darken-1" v-text="$t('delete')" @click="emit('delete')" />
				<v-btn color="secondary darken-1" v-text="$t('cancel')" @click.stop="emit('cancel')" />
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>