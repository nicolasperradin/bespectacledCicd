<script setup lang="ts">
import { ref } from 'vue'

import ConfirmDelete from '@/components/common/ConfirmDelete.vue'

defineProps<{ actions?: ('show' | 'update' | 'delete')[] }>()

const emit = defineEmits<{
	(e: 'show'): void
	(e: 'update'): void
	(e: 'delete'): void
}>()

const confirm = ref(false)
</script>

<template>
	<v-tooltip :text="$t('show')">
		<template #activator="{ props }">
			<v-btn v-bind="props" v-if="actions?.includes('show')" color="primary" size="x-small" icon="fa fa-eye" @click="emit('show')" />
		</template>
	</v-tooltip>

	<v-tooltip :text="$t('edit')">
		<template #activator="{ props }">
			<v-btn v-bind="props" v-if="actions?.includes('update')" color="warning" size="x-small" icon="fa fa-pen" @click="emit('update')" />
		</template>
	</v-tooltip>

	<v-tooltip :text="$t('delete')">
		<template #activator="{ props }">
			<v-btn v-bind="props" v-if="actions?.includes('delete')" color="danger" size="x-small" icon="fa fa-trash" @click="confirm = !confirm" />
		</template>
	</v-tooltip>

	<ConfirmDelete v-if="actions?.includes('delete')" :show="confirm" @delete="emit('delete')" @cancel="confirm = !confirm" />
</template>