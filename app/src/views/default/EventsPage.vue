<script setup lang="ts">
import { computed, onBeforeUnmount, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { storeToRefs } from 'pinia'
import { useRouter } from 'vue-router'

import type { Event } from '@/types/event'
import type { VuetifyOrder } from '@/types/list'
import { useEventListStore } from '@/store/event/list'
import { useBreadcrumb } from '@/composables/breadcrumb'
import { useMercureList } from '@/composables/mercureList'
import { useEventDeleteStore } from '@/store/event/delete'
import ActionCell from '@/components/common/ActionCell.vue'

// import EventService from '@/services/event.service'
// import VenueService from '@/services/venue.service'
import EventCard from '@/components/custom/EventCard.vue'

const parallax = new URL('@/assets/stadium.jpeg', import.meta.url).href

const { t } = useI18n()
const router = useRouter()
const breadcrumb = [{ title: t('home'), to: '/' }, ...useBreadcrumb()]

const eventDeleteStore = useEventDeleteStore()
const { deleted, mercureDeleted } = storeToRefs(eventDeleteStore)

const eventListStore = useEventListStore()
const { items, totalItems, error, isLoading } = storeToRefs(eventListStore)

const page = ref('1')
const order = ref({})
const menus = ref([])
const events = ref([])

const sendRequest = async () => await eventListStore.getItems({ page: page.value, order: order.value })

useMercureList({ store: eventListStore, deleteStore: eventDeleteStore })

sendRequest().then(() => {
	items.value.map((event: Event) => {
		event.schedules = event.schedules
		.filter(s => Date.now() < new Date(s.date).getTime())
		.sort((a, b) => new Date(a.date).getTime() - new Date(b.date).getTime())
	})

	const eventsWithoutSchedules = items.value.filter(event => event.schedules.length === 0)
	const eventsWithSchedules = items.value.filter(event => event.schedules.length > 0)

	items.value = [...eventsWithSchedules, ...eventsWithoutSchedules]
})

const headers = [
	{ title: t('actions'), key: 'actions', sortable: false },
	{ title: t('event.src'), key: 'src', sortable: false },
	{ title: t('event.type'), key: 'type', sortable: false },
	{ title: t('event.price'), key: 'price', sortable: false },
	{ title: t('event.venue'), key: 'venue', sortable: false },
	{ title: t('event.artists'), key: 'artists', sortable: false },
	{ title: t('event.schedules'), key: 'schedules', sortable: false },
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

const goToShowPage = (item: Event) => router.push({ name: 'EventShow', params: { id: item.id } })

onBeforeUnmount(() => eventDeleteStore.$reset())

const onIntersect = {
	handler: (b: any, e: any) => {
		e[0].target.style.transition = 'opacity .3s ease'
		e[0].target.style.opacity = e[0].intersectionRatio * 2
	},
	options: { threshold: [0, .25, .5, .75, 1] }
}
</script>

<template>
	<v-progress-linear :active="isLoading" color="deep-purple" height="4" indeterminate />

	<v-parallax :src="parallax">
		<div class="d-flex flex-column fill-height justify-center align-center">
			<div class="text-h2 font-weight-thin mb-4">BeSpectacled Events</div>
			<div class="text-h4 text-secondary">Discover our events</div>

			<v-btn
				color="primary"
				prepend-icon="fa fa-fade fa-computer-mouse"
				append-icon="fa fa-bounce fa-arrow-down"
				size="x-large"
				v-scroll-to="'.v-row'"
			/>
		</div>
	</v-parallax>

	<v-row justify="space-around">
		<v-col v-for="e in items" :key="e.id" class="text-center" v-intersect="onIntersect">
			<EventCard :event="e" />
		</v-col>
	</v-row>
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