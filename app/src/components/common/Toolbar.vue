<script setup lang="ts">
import { ref, toRefs } from 'vue'

import type { BreadcrumbValue } from '@/types/breadcrumb'
import Breadcrumb from '@/components/common/Breadcrumb.vue'
import ConfirmDelete from '@/components/common/ConfirmDelete.vue'

const props = defineProps<{
	actions?: ('submit' | 'reset' | 'delete' | 'add' | 'edit')[]
	isLoading: boolean
	breadcrumb: BreadcrumbValue[]
}>()

// TODO remove if not needed (see ActionCell.vue)
const { actions } = toRefs(props)

const emit = defineEmits<{
	(e: 'submit'): void
	(e: 'reset'): void
	(e: 'add'): void
	(e: 'edit'): void
	(e: 'delete'): void
}>()

const confirm = ref(false)
</script>

<template>
	<v-toolbar class="px-4 d-flex justify-space-around" elevation="0">
		<Breadcrumb :breadcrumb="breadcrumb" />

		<v-spacer />

		<div>
			<v-btn v-if="actions?.includes('add')" icon="fa fa-plus-circle" color="primary" @click="emit('add')" />

			<v-btn v-if="actions?.includes('edit')" color="warning" class="ml-sm-2" @click="emit('edit')">
				{{ $t('edit') }}
			</v-btn>

			<v-btn v-if="actions?.includes('delete')" color="error" class="ml-sm-2" @click="confirm = !confirm">
				{{ $t('delete') }}
			</v-btn>
		</div>

		<ConfirmDelete v-if="actions?.includes('delete')" :show="confirm" @delete="emit('delete')" @cancel="confirm = !confirm" />
	</v-toolbar>
</template>