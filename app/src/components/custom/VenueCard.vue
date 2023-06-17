<script setup lang="ts">
import { toRef } from 'vue'

const props = defineProps<{ venue: object }>()

// TODO transform to const
// let show = toRef(props, 'show')

const emit = defineEmits<{
	(e: 'cancel'): void
	(e: 'book'): void
}>()

// const slides = [
// 	new URL('@/assets/maestro.jpeg', import.meta.url).href,
// 	new URL('@/assets/carnival.jpeg', import.meta.url).href,
// 	new URL('@/assets/concert.jpeg', import.meta.url).href,
// 	new URL('@/assets/neon-lights.jpeg', import.meta.url).href,
// 	new URL('@/assets/theatre.jpeg', import.meta.url).href
// ]
</script>

<template>
	<!-- <v-dialog v-model="show" width="300"> -->
		<v-card class="mx-auto my-12" width="350" rounded>
			<template #loader="{ isActive }">
				<v-progress-linear :active="isActive" color="primary" indeterminate />
			</template>

			<v-img src="https://cdn.vuetifyjs.com/images/cards/cooking.png" height="250" cover>
				<!-- <v-toolbar color="rgba(0, 0, 0, 0)" theme="dark">
					<template #prepend>
						<v-btn icon="fa fa-arrow-left" />
					</template>

					<template #append>
						<v-btn icon="fa fa-ellipsis-vertical" />
					</template>
				</v-toolbar> -->
			</v-img>

			<!-- <v-carousel height="250" cycle hide-delimiters progress="primary" show-arrows="hover">
				<v-carousel-item v-for="(slide, i) in slides" :key="i" :src="slide" cover />
			</v-carousel> -->

			<v-card-item>
				<v-card-title>Mountain Retreat</v-card-title>

				<v-card-subtitle>
					<span class="me-1">Snowy Peaks, NSW, Australia</span>
					<v-icon color="danger" icon="fa fa-fire" size="small" />
				</v-card-subtitle>
			</v-card-item>

			<v-card-text>
				<v-row align="center" class="mx-0">
					<v-rating :model-value="4.5" color="amber" density="compact" half-increments readonly size="small" />
					<div class="text-grey ms-4">4.5 (413)</div>
				</v-row>
			</v-card-text>

			<v-divider class="mx-6 my-2" />

			<div class="px-4">
				<v-chip-group>
					<v-chip>5:30PM</v-chip>
					<v-chip>7:30PM</v-chip>
					<v-chip>8:00PM</v-chip>
					<v-chip>9:00PM</v-chip>
				</v-chip-group>
			</div>

			<v-divider class="mx-6 my-2" />

			<v-card-text>
				<p color="primary">Experience the wonders of nature as you hike through the snow-capped mountains and fish in the nearby river. This newly built cabin is perched high in the mountains with beautiful views. It comfortably sleeps 4 people and includes a dining room, living room, sauna, and fully stocked kitchen.</p>
			</v-card-text>

			<v-card-actions>
				<v-btn color="primary" variant="elevated" v-text="'Book Now'" @click="emit('book')" />
				<v-spacer />
				<div><b>$299.99</b><small> / night</small></div>
			</v-card-actions>
		</v-card>
	<!-- </v-dialog> -->
</template>