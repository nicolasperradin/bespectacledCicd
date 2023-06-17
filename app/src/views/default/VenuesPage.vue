<script setup>
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { storeToRefs } from 'pinia'
import { useRouter } from 'vue-router'

// import type { Venue } from '@/types/venue'
// import type { VuetifyOrder } from '@/types/list'
import { useVenueListStore } from '@/store/venue/list'
import { useBreadcrumb } from '@/composables/breadcrumb'
import { useMercureList } from '@/composables/mercureList'
import { useVenueDeleteStore } from '@/store/venue/delete'

import EventService from '@/services/event.service'
import VenueService from '@/services/venue.service'
import VenueCard from '@/components/custom/VenueCard.vue'

const parallax = new URL('@/assets/stadium.jpeg', import.meta.url).href

const menus = ref({})
const events = ref([])
const groups = ref({})
const sortedVenues = ref({})

const confirm = ref(false)

onMounted(async () => {
	const { data } = await EventService.all()
	const { data: venues } = await VenueService.all()

	events.value = data

	// Group venues by type
	sortedVenues.value = venues.reduce((acc, cur) => {
		// menus.value = Array(calendarSchedule.value.length).fill([])
		if (!menus.value[cur.type]) menus.value[cur.type] = Array.from({ length: data.length }, () => false) // as many menus as events so we have one menu per event
		if (!groups.value[cur.type]) groups.value[cur.type] = null // default selected model
		if (!acc[cur.type]) acc[cur.type] = []
		acc[cur.type].push(cur)
		return acc
	}, {})
})

const onIntersect = {
	handler: (b, e) => {
		e[0].target.style.transition = 'opacity .3s ease'
		e[0].target.style.opacity = e[0].intersectionRatio
	},
	options: { threshold: [0, .25, .5, .75, 1] }
}
</script>

<template>
	<v-parallax class="snap" :src="parallax">
		<div class="d-flex flex-column fill-height justify-center align-center">
			<div class="text-h2 font-weight-thin mb-4">BeSpectacled Venues</div>
			<div class="text-h4 text-secondary">Meet your favorite artists in our venues</div>

			<v-btn
				color="primary"
				prepend-icon="fa fa-fade fa-computer-mouse"
				append-icon="fa fa-bounce fa-arrow-down"
				size="x-large"
				v-scroll-to="'.v-sheet'"
			/>
		</div>
	</v-parallax>

	<!-- <v-btn
		color="primary"
		:icon="confirm ? 'fa fa-eye-slash' : 'fa fa-eye'"
		size="x-large"
		@click="confirm = !confirm"
	/>

	<VenueCard :venue="venue" @book="confirm = !confirm" @cancel="confirm = !confirm" /> -->

	<v-sheet v-for="key in Object.keys(sortedVenues)" :key="key" class="mx-auto snap" elevation="8" :class="key === Object.keys(sortedVenues)[0] ? 'mb-4' : ''">
		<div class="text-h4 font-weight-thin px-4 pt-4">For {{ key }}s</div>
		<v-slide-group v-model="groups[key]" selected-class="bg-primary border-lg" show-arrows v-intersect="onIntersect">
			<v-slide-group-item v-for="{ id, name, src } in sortedVenues[key]" :key="id" v-slot="{ isSelected, toggle, selectedClass }">
				<v-card color="grey-lighten-1" :class="['ma-4 d-flex', selectedClass]" height="200" width="200" @click="toggle">
					<v-img :src="src" cover class="d-flex fill-height align-center text-center">
						<v-scale-transition>
							<v-icon v-if="isSelected" color="white" size="48" icon="fa fa-times-circle">{{ name }}</v-icon>
						</v-scale-transition>
					</v-img>
				</v-card>
			</v-slide-group-item>
		</v-slide-group>

		<v-expand-transition>
			<v-sheet v-if="groups[key] != null" class="d-flex flex-column fill-height justify-center align-center text-white">
				<div class="text-h4 font-weight-thin">{{ sortedVenues[key][groups[key]].name }}</div>
				<div class="text-h6 text-secondary">{{ sortedVenues[key][groups[key]].seats }} seats</div>

				<!-- TODO v-notify="[sortedVenues[key][groups[key]], ' succesfully booked!']" -->
				<v-btn color="primary" prepend-icon="fa fa-key fa-flip" @click="() => $router.push({ name: 'ticketing', query: { venue: sortedVenues[key][groups[key]].id } })">
					Book for ${{ sortedVenues[key][groups[key]].price }}
				</v-btn>

				<div class="text-overline">Events at this venue</div>

				<v-card-text>
					<v-chip-group column>
						<v-menu v-for="({ id, title, price, src }, i) in events.filter(e => e.venue === sortedVenues[key][groups[key]].id)" :key="title" v-model="menus[key][i]" location="top start" origin="top start" transition="scale-transition">
							<template #activator="{ props }">
								<v-chip v-bind="props" pill link>
									<v-avatar start :image="src" />
									{{ title }}
								</v-chip>
							</template>

							<v-card width="max-content">
								<v-list bg-color="black">
									<v-list-item :title="title" :subtitle="`Min. $${price} per seat`" :prepend-avatar="src">
										<template #append>
											<v-list-item-action>
												<v-btn icon variant="text" @click="menus[key][i] = false">
													<v-icon icon="fa fa-times-circle" />
												</v-btn>
											</v-list-item-action>
										</template>
									</v-list-item>
								</v-list>

								<v-list>
									<v-list-item link prepend-icon="fa fa-circle-info" title="View Event Details" @click="() => $router.push({ name: 'event', params: { id } })" />
									<v-list-item link prepend-icon="fa fa-calendar-days" title="Check Available Times" @click="() => $router.push({ name: 'calendar', query: { event: id } })" />
									<v-list-item link prepend-icon="fa fa-ticket" title="Buy Tickets" @click="() => $router.push({ name: 'ticketing', query: { event: id } })" />
								</v-list>
							</v-card>
						</v-menu>
					</v-chip-group>
				</v-card-text>
			</v-sheet>
		</v-expand-transition>
	</v-sheet>
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
