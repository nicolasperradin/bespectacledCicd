<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

import EventService from '../../services/event.service'
import ScheduleService from '../../services/schedule.service'

const $store = useStore()
const $router = useRouter()

const query = $router.currentRoute.value.query
const parallax = new URL('@/assets/stadium.jpeg', import.meta.url).href

const tab = ref(1)
const menus = ref([])
const calendar = ref(null)
const selectedEvent = ref(null)
const calendarSchedule = ref([])
const options = reactive({
	rows: 1,
	columns: 1,
	events: [],
	range: {
		start: null,
		end: null
	},
	masks: {
		input: 'YYYY-MM-DD h:mm A',
	}
})

const attrs = computed(() => {
	return [
		{ key: 'today', highlight: true, dates: new Date() },
		options.range.start && options.range.end && {
			key: 'range',
			highlight: {
				start: { fillMode: 'outline' },
				base: { fillMode: 'light' },
				end: { fillMode: 'outline' }
			},
			dates: { start: options.range.start, end: options.range.end }
		},
		...calendarSchedule.value.filter(day => day.event.id === (selectedEvent.value?.id || day.event.id)).map(event => {
		return {
			dates: event.dates,
			dot: {
				color: event.color,
				class: event.isComplete ? 'disabled' : ''
			},
			...attr(event.description, 'focus'),
			customData: event,
			...event
		}
	})]
})

onMounted(async () => {
	const { data: events } = await EventService.all()
	const { data: schedule } = await ScheduleService.all()

	options.events = events
	selectedEvent.value = events.find(e => e.id === Number(query?.event))
	if (query?.start) options.range.start = new Date(query?.start)
	if (query?.end) options.range.end = new Date(query?.end)

	calendarSchedule.value = schedule.map(day => {
		const event = events.find(e => e.id === day.event)

		if (!menus.value[day.id]) menus.value[day.id] = Array.from(day.times.length).fill(null)

		return {
			description: event.title,
			dates: day.times.map(time => day.date + 'T' + time),
			color: randomColor(event.title),
			isComplete: false,
			event,
			day
		}
	})
})

const attr = (label = null, visibility = 'hover') => {
	return {
		popover: {
			label,
			visibility,
			isInteractive: true,
			placement: 'auto'
		}
	}
}

const randomColor = title => {
	const colors = ['gray', 'red', 'orange', 'yellow', 'green', 'teal', 'blue', 'indigo', 'purple', 'pink']
	return colors[title.length % colors.length]
}
</script>

<template>
	<v-parallax :src="parallax">
		<div class="d-flex flex-column fill-height justify-center align-center">
			<div class="text-h2 font-weight-thin mb-4">BeSpectacled Schedule</div>
			<div class="text-h4 text-secondary">Take a look at our upcoming events</div>
		</div>
	</v-parallax>

	<v-row>
		<v-col cols="4">
			<v-sheet rounded class="fill-height">
				<v-card-text>
					<!-- <div v-if="calendar" class="text-overline">{{ calendar.firstPage.title }}</div> -->

					<div class="d-flex justify-space-between align-center mb-2">
						<!-- <v-btn variant="text" color="grey darken-2" icon="fa fa-chevron-left" @click="() => calendar.move(-1)" /> -->
						<v-btn variant="outlined" color="grey darken-2" @click="() => calendar.focusDate(new Date())">Today</v-btn>
						<!-- <v-btn variant="text" color="grey darken-2" icon="fa fa-chevron-right" @click="() => calendar.move(1)" /> -->
					</div>

					<v-combobox
						v-model="selectedEvent"
						clearable
						chips
						label="Filter by event"
						:items="options.events"
						hide-selected
					/>

					<v-date-picker
						v-model="options.range"
						mode="date"
						color="purple"
						:is-dark="$store.state.theme.dark"
						:masks="options.masks"
						is-range
						:select-attribute="attr()"
						:drag-attribute="attr()"
						@drag="options.range = $event"
					>	
						<template v-slot:day-popover="{ format }">
							{{ format(options.range.start, 'MMM D') }}
							-
							{{ format(options.range.end, 'MMM D') }}
						</template>

						<template v-slot="{ inputEvents, isDragging }">
							<div class="d-flex flex-column flex-wrap">
								<v-text-field
									:prepend-inner-icon="isDragging ? 'fa fa-bullseye text-primary' : 'fa fa-calendar'"
									label="Minimum Date"
									v-model="options.range.start"
									v-on="inputEvents.start"
									clearable
									hide-details
								/>

								<v-text-field
									:prepend-inner-icon="isDragging ? 'fa fa-bullseye text-primary' : 'fa fa-calendar'"
									label="Maximum Date"
									v-model="options.range.end"
									v-on="inputEvents.end"
									clearable
									hide-details
								/>
							</div>
						</template>
					</v-date-picker>
				</v-card-text>
			</v-sheet>
		</v-col>

		<v-col>
			<v-calendar
				ref="calendar"
				class="fill-height"
				color="purple"
				:rows="options.rows"
				:columns="options.columns"
				:min-date="options.range.start"
				:max-date="options.range.end"
				is-expanded
				:is-dark="$store.state.theme.dark"
				:attributes="attrs"
				nav-visibility="hover"
			>
				<template #day-popover="{ day, format, attributes }">
					<div class="text-overline text-gray-300 font-semibold text-center">
						{{ format(day.date, 'WWWW, MMMM D, YYYY') }}
					</div>

					<v-tabs v-model="tab" color="primary" stacked show-arrows>
						<v-tab v-for="attr in attributes" :key="attr.key" :value="attr.customData.event.id">
							<v-avatar start :image="attr.customData.event.src" />
						</v-tab>
					</v-tabs>

					<v-window v-model="tab">
						<v-window-item v-for="attr in attributes" :key="attr.key" :value="attr.customData.event.id">
							<v-card flat :title="attr.customData.event.title" :subtitle="`Min. ${attr.customData.event.price} per seat`" :append-avatar="attr.customData.event.src">
								<v-card-text v-if="attr.customData.day.times.length > 0">
									<div class="text-overline">Starting {{ attr.customData.day.times.length > 1 ? 'Times' : 'Time' }}</div>

									<v-chip-group column>
										<v-menu
											v-for="(time, i) in attr.customData.day.times"
											:key="i"
											v-model="menus[attr.customData.day.id][i]"
											location="top start"
											origin="top start"
											transition="scale-transition"
											:disabled="Date.now() > new Date(attr.customData.day.date + 'T' + time).getTime()"
										>
											<template v-slot:activator="{ props }">
												<v-chip v-bind="props" pill link :text="time" :disabled="Date.now() > new Date(attr.customData.day.date + 'T' + time).getTime()" />
											</template>

											<v-card width="max-content">
												<v-list bg-color="black">
													<v-list-item :title="time" :subtitle="format(day.date, 'WWWW, MMMM D, YYYY')">
														<template v-slot:append>
															<v-list-item-action>
																<v-btn icon="fa fa-times-circle" variant="text" @click="menus[attr.customData.day.id][i] = false" />
															</v-list-item-action>
														</template>
													</v-list-item>
												</v-list>

												<v-list>
													<v-list-item link prepend-icon="fa fa-ticket" title="Buy Tickets" @click="() => $router.push({ name: 'ticketing', query: { event: attr.customData.event.id, time } })" />
												</v-list>
											</v-card>
										</v-menu>
									</v-chip-group>
								</v-card-text>

								<v-card-actions>
									<v-btn text color="secondary" @click="() => $router.push({ name: 'event', params: { id: attr.customData.event.id } })">View Event Details</v-btn>
									<v-spacer></v-spacer>
									<v-btn text color="primary" @click="() => $router.push({ name: 'ticketing', query: { event: attr.customData.event.id } })">Buy Tickets</v-btn>
								</v-card-actions>
							</v-card>
						</v-window-item>
					</v-window>
				</template>
			</v-calendar>
		</v-col>
	</v-row>
</template>

<style scoped>
.v-parallax {
	height: calc(50vh - (48px + 16px * 2)) !important;
	margin-bottom: 16px;
}

.v-parallax .v-btn {
	position: absolute;
	bottom: 16px;
}

.v-col:last-child {
	height: calc(50vh) !important
}
</style>