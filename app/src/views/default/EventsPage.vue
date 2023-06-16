<script setup lang="ts">
import { computed, onBeforeUnmount, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { storeToRefs } from 'pinia'
import { useRouter } from 'vue-router'

import type { Event } from '@/types/event'
import { useEventListStore } from '@/store/event/list'
import EventCard from '@/components/custom/EventCard.vue'
import { useMercureList } from '@/composables/mercureList'
import { useEventDeleteStore } from '@/store/event/delete'
import DataIterator from '@/components/custom/DataIterator.vue'

const parallax = new URL('@/assets/stadium.jpeg', import.meta.url).href

const { t } = useI18n()
const $router = useRouter()

const deleteStore = useEventDeleteStore()
const { deleted, mercureDeleted } = storeToRefs(deleteStore)

const store = useEventListStore()
const { items, totalItems, error, isLoading } = storeToRefs(store)

const page = ref('1')
const order = ref({})
const keys = ['Title', 'Type', 'Price', 'Artists', 'Schedules']

const sendRequest = async () => await store.getItems({ page: page.value, order: order.value })

useMercureList({ store, deleteStore })

sendRequest().then(() => {
	items.value.map((event: Event) => {
		event.schedules = event.schedules
		.filter(s => Date.now() < new Date(s.date).getTime())
		.sort((a, b) => new Date(a.date).getTime() - new Date(b.date).getTime())
	})

	const eventsWithSchedules = items.value.filter(event => event.schedules.length > 0)
	const eventsWithoutSchedules = items.value.filter(event => event.schedules.length === 0)

	items.value = [...eventsWithSchedules, ...eventsWithoutSchedules]
})

// const updatePage = (newPage: string) => {
// 	page.value = newPage
// 	sendRequest()
// }

// const updateOrder = (newOrders: VuetifyOrder[]) => {
// 	const newOrder = newOrders[0]
// 	order.value = { [newOrder.key]: newOrder.order }
// 	sendRequest()
// }

// const goToShowPage = (item: Event) => $router.push({ name: 'EventShow', params: { id: item.id } })

onBeforeUnmount(() => deleteStore.$reset())
</script>

<template>
	<v-progress-linear :active="isLoading" color="white" height="4" indeterminate />

	<v-parallax class="snap" :src="parallax">
		<div class="d-flex flex-column fill-height justify-center align-center">
			<div class="text-h2 font-weight-thin mb-4">Find your next event</div>
			<div class="text-h4 text-secondary">From concerts to broadway shows, there's an event for you.</div>

			<v-btn
				color="primary"
				prepend-icon="fa fa-fade fa-computer-mouse"
				append-icon="fa fa-bounce fa-arrow-down"
				size="x-large"
				v-scroll-to="'.v-data-iterator'"
			/>
		</div>
	</v-parallax>

	<DataIterator #="props" :items="items" :keys="keys" :isLoading="isLoading" :availability="_ => _.schedules.length > 0">
		<v-col v-if="!isLoading" v-for="e in props.items" :key="e.raw.id" class="text-center snap" v-intersect="props.onIntersect">
			<EventCard :event="e.raw" />
		</v-col>
	</DataIterator>
</template>

<style scoped>
.v-parallax {
	height: calc(100vh - (48px + 16px * 2)) !important;
	margin-bottom: 16px;
}

.v-parallax .v-btn {
	position: absolute;
	bottom: 16px;
}
</style>
