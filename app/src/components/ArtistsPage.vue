<script setup>
import { ref } from 'vue'

const model = ref(null)

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
				v-scroll-to="'.v-row'"
			/>
		</div>
	</v-parallax>

	<v-row>
		<v-col v-for="n in 24" :key="n" cols="4">
			<v-card height="200" v-intersect="onIntersect" />
		</v-col>
	</v-row>
	<v-sheet class="mx-auto" elevation="8" max-width="800">
		<v-slide-group v-model="model" class="pa-4" selected-class="bg-primary" show-arrows>
			<v-slide-group-item v-for="n in 15" :key="n" v-slot="{ isSelected, toggle, selectedClass }">
				<v-card color="grey-lighten-1" :class="['ma-4', selectedClass]" height="200" width="100" @click="toggle">
					<div class="d-flex fill-height align-center justify-center">
						<v-scale-transition>
							<v-icon v-if="isSelected" color="white" size="48" icon="mdi-close-circle-outline"></v-icon>
						</v-scale-transition>
					</div>
				</v-card>
			</v-slide-group-item>
		</v-slide-group>
	
		<v-expand-transition>
			<v-sheet v-if="model != null" height="200">
				<div class="d-flex fill-height align-center justify-center">
					<h3 class="text-h6">
						Selected {{ model }}
					</h3>
				</div>
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