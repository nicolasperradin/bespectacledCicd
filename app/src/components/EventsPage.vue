<script setup>
// const onIntersect = {
// 	handler: (b, e) => e[0].target.style.opacity = e[0].intersectionRatio,
// 	options: { threshold: [0, .25, .5, .75, 1] }
// }
</script>

<template>
	<v-parallax src="https://cdn.vuetifyjs.com/images/backgrounds/vbanner.jpg">
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

	<v-timeline align="start">
		<v-timeline-item v-for="(item, i) in items" :key="i" :dot-color="item.color" :icon="item.icon" fill-dot>
			<v-card>
				<v-card-title :class="['text-h6', `bg-${item.color}`]">
					Lorem Ipsum Dolor
				</v-card-title>
				<v-card-text class="bg-white text--primary">
					<p>Lorem ipsum dolor sit amet, no nam oblique veritus. Commune scaevola imperdiet nec ut, sed euismod
						convenire principes at. Est et nobis iisque percipit, an vim zril disputando voluptatibus, vix an
						salutandi sententiae.</p>
					<v-btn :color="item.color" variant="outlined">
						Button
					</v-btn>
				</v-card-text>
			</v-card>
		</v-timeline-item>
	</v-timeline>
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