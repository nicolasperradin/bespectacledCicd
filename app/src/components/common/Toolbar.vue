<script setup lang="ts">
import { ref, toRefs } from 'vue'

import type { BreadcrumbValue } from '@/types/breadcrumb'
import Breadcrumb from '@/components/common/Breadcrumb.vue'
import ConfirmDelete from '@/components/common/ConfirmDelete.vue'

defineProps<{
	actions?: ('submit' | 'reset' | 'delete' | 'add' | 'edit')[]
	isLoading: boolean
	breadcrumb: BreadcrumbValue[]
}>()

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
	<v-toolbar class="mb-4 sticky-top sticky-nav" color="pink-accent-4" dark rounded>
		<Breadcrumb :breadcrumb="breadcrumb" />

		<v-spacer />

		<v-btn v-if="actions?.includes('add')" class="fill-height m-0 bg-success rounded-0" prepend-icon="fa fa-plus-circle" :text="$t('add')" @click="emit('add')" />
		<v-btn v-if="actions?.includes('edit')" class="fill-height m-0 bg-warning rounded-0" prepend-icon="fa fa-pen-to-square" :text="$t('edit')" @click="emit('edit')" />
		<v-btn v-if="actions?.includes('delete')" class="fill-height m-0 bg-danger rounded-0" prepend-icon="fa fa-trash-can" :text="$t('delete')" @click="confirm = !confirm" />

		<ConfirmDelete v-if="actions?.includes('delete')" :show="confirm" @delete="() => { confirm = !confirm; emit('delete') }" @cancel="confirm = !confirm" />
	</v-toolbar>
</template>