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
	<v-btn v-if="actions?.includes('show')" color="secondary" size="small" class="ma-2" @click="emit('show')">
		{{ $t("show") }}
	</v-btn>

	<v-btn v-if="actions?.includes('update')" color="secondary" size="small" class="ma-2" @click="emit('update')">
		{{ $t("edit") }}
	</v-btn>

	<v-btn v-if="actions?.includes('delete')" color="secondary" size="small" class="ma-2" @click="confirm = !confirm">
		{{ $t("delete") }}
	</v-btn>

	<ConfirmDelete v-if="actions?.includes('delete')" :show="confirm" @delete="emit('delete')" @cancel="confirm = !confirm" />
</template>