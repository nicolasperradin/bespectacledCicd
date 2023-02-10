<script setup>
import { ref, onMounted } from 'vue'

import EventService from '../services/event.service'
import VenueService from '../services/venue.service'

const events = ref([])
const models = ref({})
const menus = ref({})
const chips = ref({})
const sortedVenues = ref({})
// const filteredEvents = computed(() => {
// 	if (!models.value) return []
// 	return events.filter(e => e.venue === sortedVenues.value[models.value].id)
// })

onMounted(async () => {
	const { data } = await EventService.all()
	const { data: venues } = await VenueService.all()

	events.value = data

	// for each event
	menus.value = Array.from({ length: data.length }, () => false)

	// Group venues by type
	sortedVenues.value = venues.reduce((acc, cur) => {
		if (!menus.value[cur.type]) menus.value[cur.type] = Array.from({ length: data.length }, () => false) // as many menus as events so we have one menu per event
		if (!chips.value[cur.type]) chips.value[cur.type] = null // null because no chip should be selected by default
		if (!models.value[cur.type]) models.value[cur.type] = 0 // 0 for the first venue of each group to be selected by default
		if (!acc[cur.type]) acc[cur.type] = []
		acc[cur.type].push(cur)
		return acc
	}, {})
})

const onIntersect = {
	handler: (b, e) => e[0].target.style.opacity = e[0].intersectionRatio,
	options: { threshold: [0, .25, .5, .75, 1] }
}
</script>

<template>
	<v-parallax :src="require('@/assets/stadium.jpeg')">
		<div class="d-flex flex-column fill-height justify-center align-center text-white">
			<h1 class="text-h4 font-weight-thin mb-4">BeSpectacled</h1>
			<h4>Book tickets for events, concerts, and more!</h4>

			<v-btn color="primary"
				prepend-icon="fa fa-fade fa-computer-mouse"
				append-icon="fa fa-bounce fa-arrow-down"
				size="x-large"
				v-scroll-to="'.v-sheet'"
			/>
		</div>
	</v-parallax>

	<v-sheet v-for="key in Object.keys(sortedVenues)" :key="key" class="mx-auto" elevation="8" :class="key === Object.keys(sortedVenues)[0] ? 'mb-4' : ''">

		<v-slide-group v-model="models[key]" class="pa-4" selected-class="bg-primary border-lg" center-active mandatory show-arrows v-intersect="onIntersect">
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
			<v-sheet v-if="models[key] != null" class="d-flex flex-column fill-height justify-center align-center text-white">
				<div class="text-h4 font-weight-thin">{{ sortedVenues[key][models[key]].name }}</div>
				<div class="text-h6 text-secondary">{{ sortedVenues[key][models[key]].seats }} seats</div>

				<v-divider />

				<div class="text-overline">Events at this venue</div>

				<v-card-text>
					<v-chip-group v-model="chips[key]" selected-class="text-deep-purple-accent-4" mandatory column>
						<v-menu v-for="({ id, title, price, src }, i) in events.filter(e => e.venue === sortedVenues[key][models[key]].id)" :key="title" v-model="menus[key][i]" location="top start" origin="top start" transition="scale-transition">
							<template v-slot:activator="{ props }">
								<v-chip pill v-bind="props" link>
									<v-avatar start :image="src" />
									{{ title }}
								</v-chip>
							</template>

							<v-card width="max-content">
								<v-list bg-color="black">
									<v-list-item>
										<template v-slot:prepend>
											<v-avatar :image="src"></v-avatar>
										</template>
							
										<v-list-item-title>{{ title }}</v-list-item-title>
										<v-list-item-subtitle>Min. ${{ price }} per seat</v-list-item-subtitle>
							
										<template v-slot:append>
											<v-list-item-action>
												<v-btn icon variant="text" @click="menus[key][i] = false">
													<v-icon icon="fa fa-times-circle" />
												</v-btn>
											</v-list-item-action>
										</template>
									</v-list-item>
								</v-list>
							
								<v-list>
									<v-list-item link prepend-icon="fa fa-circle-info" title="See Event Details" @click="() => $router.push({ name: 'event', params: { id } })" />
									<v-list-item link prepend-icon="fa fa-calendar-days" title="Open Schedule" @click="() => alert('This feature has not been implemented yet.')" />
									<v-list-item link prepend-icon="fa fa-ticket" title="Buy Tickets" @click="() => alert('This feature has not been implemented yet.')" />
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
	height: calc(100vh - (48px + 16px * 2));
	margin-bottom: 16px;
}

.v-parallax .v-btn {
	position: absolute;
	bottom: 16px;
}
</style>