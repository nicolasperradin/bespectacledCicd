<script setup lang="ts">
import { onBeforeUnmount } from 'vue'
import { useI18n } from 'vue-i18n'
import { storeToRefs } from 'pinia'
import type { Event } from '@/types/event'
import { useRoute, useRouter } from 'vue-router'
import Form from '@/components/event/EventForm.vue'
import Loading from '@/components/common/Loading.vue'
import Toolbar from '@/components/common/Toolbar.vue'
import { useBreadcrumb } from '@/composables/breadcrumb'
import { useMercureItem } from '@/composables/mercureItem'
import { useEventCreateStore } from '@/store/event/create'
import { useEventDeleteStore } from '@/store/event/delete'
import { useEventUpdateStore } from '@/store/event/update'

const { t } = useI18n()
const route = useRoute()
const router = useRouter()
const breadcrumb = useBreadcrumb()

const eventCreateStore = useEventCreateStore()
const { created } = storeToRefs(eventCreateStore)

const eventDeleteStore = useEventDeleteStore()
const { isLoading: deleteLoading, error: deleteError } = storeToRefs(eventDeleteStore)

const eventUpdateStore = useEventUpdateStore()
const { retrieved: item, updated, isLoading, error, violations, } = storeToRefs(eventUpdateStore)

useMercureItem({ store: eventUpdateStore, deleteStore: eventDeleteStore, redirectRouteName: 'EventList' })

await eventUpdateStore.retrieve(decodeURIComponent(route.params.id as string))

const update = async (item: Event) => await eventUpdateStore.update(item)

const deleteItem = async () => {
  if (!item?.value) return eventUpdateStore.setError(t('itemNotFound'))
  await eventDeleteStore.deleteItem(item?.value)
  router.push({ name: 'EventList' })
}

onBeforeUnmount(() => {
	eventUpdateStore.$reset()
	eventCreateStore.$reset()
	eventDeleteStore.$reset()
})
</script>

<template>
	<Toolbar :actions="['delete']" :breadcrumb="breadcrumb" :is-loading="isLoading" @delete="deleteItem" />

	<v-container fluid>
		<v-alert v-if="error || deleteError" type="error" class="mb-4" v-text="error || deleteError" closable />

		<v-alert v-if="created || updated" type="success" class="mb-4" closable>
			<template v-if="updated">
				{{ $t('itemUpdated', [updated['@id']]) }}
			</template>

			<template v-else-if="created">
				{{ $t('itemCreated', [created['@id']]) }}
			</template>
		</v-alert>

		<Form v-if="item" :values="item" :errors="violations" @submit="update" />
	</v-container>

	<Loading :visible="isLoading || deleteLoading" />
</template>