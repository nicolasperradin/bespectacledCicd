<script setup>
import { ref } from 'vue'
import stadium from '@/assets/stadium.jpeg'

const model = ref(null)

const onIntersect = {
	handler: (b, e) => {
		e[0].target.style.transition = 'opacity .3s ease'
		e[0].target.style.opacity = e[0].intersectionRatio
	},
	options: { threshold: [0, .25, .5, .75, 1] }
}
</script>

<template>
	<v-parallax :src="stadium">
		<div class="d-flex flex-column fill-height justify-center align-center text-white">
			<h1 class="text-h4 font-weight-thin mb-4">BeSpectacled</h1>
			<h4>Book tickets for events, concerts, and more!</h4>

			<v-btn
				color="primary"
				prepend-icon="fa fa-fade fa-computer-mouse"
				append-icon="fa fa-bounce fa-arrow-down"
				size="x-large"
				v-scroll-to="'.v-row'"
			/>
		</div>
	</v-parallax>

	<v-row>
		<v-col v-for="n in 9" :key="n" cols="4">
			<v-card height="200" v-intersect="onIntersect" />
		</v-col>
	</v-row>
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