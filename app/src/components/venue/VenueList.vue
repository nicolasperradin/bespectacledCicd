<script setup lang="ts">
import { ref, onBeforeUnmount } from 'vue'
import { useI18n } from 'vue-i18n'
import { storeToRefs } from 'pinia'
import { useRouter } from 'vue-router'

import type { Venue } from '@/types/venue'
import type { VuetifyOrder } from '@/types/list'
import Toolbar from '@/components/common/Toolbar.vue'
// import { useVenueListStore } from '@/store/venue/list'
import { useBreadcrumb } from '@/composables/breadcrumb'
import { useMercureList } from '@/composables/mercureList'
// import { useVenueDeleteStore } from '@/store/venue/delete'
import ActionCell from '@/components/common/ActionCell.vue'
import { useVenueDeleteStore, useVenueListStore } from '@/store/venue'

const { t } = useI18n()
const router = useRouter()
const breadcrumb = useBreadcrumb()

const venueDeleteStore = useVenueDeleteStore()
const { deleted, mercureDeleted } = storeToRefs(venueDeleteStore)

const venueListStore = useVenueListStore()
const { items, totalItems, error, isLoading } = storeToRefs(venueListStore)

const page = ref('1')
const order = ref({})

const icons: Record<string, string> = {
	broadway: 'fa fa-mask',
	concert: 'fa fa-microphone',
	other: 'fa fa-question'
}

const sendRequest = async () => await venueListStore.getItems({ page: page.value, order: order.value })

useMercureList({ store: venueListStore, deleteStore: venueDeleteStore })

sendRequest()

const headers = [
	{ title: t('actions'), key: 'actions', sortable: false },
	{ title: t('venue.src'), key: 'src', sortable: false },
	{ title: t('venue.type'), key: 'type', sortable: false },
	{ title: t('venue.seats'), key: 'seats', sortable: false },
	{ title: t('venue.price'), key: 'price', sortable: false },
	{ title: t('venue.events'), key: 'events', sortable: false }
]

const updatePage = (newPage: string) => {
	page.value = newPage
	sendRequest()
}

const updateOrder = (newOrders: VuetifyOrder[]) => {
	const newOrder = newOrders[0]
	order.value = { [newOrder.key]: newOrder.order }
	sendRequest()
}

const goToShowPage = (item: Venue) => router.push({ name: 'VenueShow', params: { id: item.id } })
const goToCreatePage = () => router.push({ name: 'VenueCreate' })
const goToUpdatePage = (item: Venue) => router.push({ name: 'VenueUpdate', params: { id: item.id } })

const deleteItem = async (item: Venue) => {
	await venueDeleteStore.deleteItem(item)
	sendRequest()
}

onBeforeUnmount(() => venueDeleteStore.$reset())
</script>

<template>
	<Toolbar :actions="['add']" :breadcrumb="breadcrumb" :is-loading="isLoading" @add="goToCreatePage" />

	<v-container fluid>
		<v-alert v-if="deleted" type="success" class="mb-4" v-text="$t('itemDeleted', [deleted['@id']])" closable />
		<v-alert v-if="mercureDeleted" type="success" class="mb-4" v-text="$t('itemDeletedByAnotherUser', [mercureDeleted['@id']])" closable />
		<v-alert v-if="error" type="error" class="mb-4" v-text="error" closable />

		<v-data-table-server
			:headers="headers"
			:items="items"
			:items-length="totalItems"
			:loading="isLoading"
			:items-per-page="items.length"
			@update:page="updatePage"
			@update:sortBy="updateOrder"
		>
			<template #item.actions="{ item }">
				<ActionCell :actions="['show', 'update', 'delete']" @show="goToShowPage(item.raw)" @update="goToUpdatePage(item.raw)" @delete="deleteItem(item.raw)" />
			</template>

			<!-- <template #item.@id="{ item }">
				<router-link :to="{ name: 'VenueShow', params: { id: item.raw.id } }">
					{{ item.raw["@id"] }}
				</router-link>
			</template> -->

			<template #item.src="{ item }">
				<v-tooltip :text="item.raw.name">
					<template #activator="{ props }">
						<router-link v-bind="props" :to="{ name: 'VenueShow', params: { id: item.raw.id } }">
							<v-img :src="item.raw.src" max-width="100" max-height="100" />
						</router-link>
					</template>
				</v-tooltip>
			</template>

			<template #item.type="{ item }">
				<v-tooltip :text="item.raw.type">
					<template #activator="{ props }">
						<v-icon v-bind="props" :icon="icons[item.raw.type]" />
					</template>
				</v-tooltip>
			</template>

			<template #item.seats="{ item }">
				{{ item.raw.seats }}
			</template>

			<template #item.price="{ item }">
				${{ item.raw.price }}
			</template>

			<template #item.events="{ item }">
				<v-tooltip v-for="event in item.raw.events" :text="event.title">
					<template #activator="{ props }">
						<template v-if="router.hasRoute('EventShow')">
							<router-link :key="event.id" :to="{ name: 'EventShow', params: { id: event.id } }" />
						</template>

						<template v-else>
							<v-icon v-bind="props" icon="fa fa-star" />
						</template>
					</template>
				</v-tooltip>
			</template>
		</v-data-table-server>
	</v-container>
</template>