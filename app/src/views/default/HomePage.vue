<script setup>
const slides = [
	new URL('@/assets/maestro.jpeg', import.meta.url).href,
	new URL('@/assets/carnival.jpeg', import.meta.url).href,
	new URL('@/assets/concert.jpeg', import.meta.url).href,
	new URL('@/assets/neon-lights.jpeg', import.meta.url).href,
	new URL('@/assets/theatre.jpeg', import.meta.url).href
]

const onIntersect = {
	handler: (b, e) => {
		e[0].target.style.transition = 'opacity .3s ease'
		e[0].target.style.opacity = e[0].intersectionRatio
	},
	options: { threshold: [0, .25, .5, .75, 1] }
}
</script>

<template>
	<v-carousel color="secondary" cycle hide-delimiter-background progress="primary" show-arrows="hover">
		<v-carousel-item v-for="(slide, i) in slides" :key="i" :src="slide" cover>
			<v-sheet color="transparent" class="d-flex flex-column fill-height justify-center align-center text-white">
				<div :class="[`text-${i === 5 ? 'black' : 'white'}-50`, 'blend text-h1 font-weight-thin mb-4']">BeSpectacled</div>
				<div class="blend text-h4">Book tickets for events, concerts, and more!</div>
	
				<v-btn color="primary"
					prepend-icon="fa fa-fade fa-computer-mouse"
					append-icon="fa fa-bounce fa-arrow-down"
					size="x-large"
					v-scroll-to="'.v-row'"
				/>
			</v-sheet>
		</v-carousel-item>
	</v-carousel>

	<v-row>
		<v-col v-for="n in 24" :key="n" cols="4">
			<v-card height="200" v-intersect="onIntersect" />
		</v-col>
	</v-row>
</template>

<style>
.v-carousel {
	height: calc(100vh - (48px + 16px * 2)) !important;
	margin-bottom: 16px;
}

.v-carousel .v-sheet .v-btn {
	position: absolute;
	bottom: 48px;
}

.v-carousel .v-img__img {
	object-fit: cover;
}
</style>