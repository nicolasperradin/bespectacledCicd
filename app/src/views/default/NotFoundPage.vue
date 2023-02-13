<script setup>
const parallax = new URL('@/assets/404.png', import.meta.url).href
</script>

<template>
	<v-parallax :src="parallax">
		<div class="d-flex flex-column fill-height justify-center align-center text-white">
			<h1 class="text-primary text-h1 font-weight-thin mb-4">Uh oh!</h1>
			<h4 class="text-secondary">This page doesn't exist.</h4>

			<v-btn color="primary"
				prepend-icon="fa fa-beat fa-arrow-left"
				append-icon="fa fa-fade fa-home"
				size="x-large"
				@click="() => $router.push({ name: 'home' })"
			/>
		</div>
	</v-parallax>
</template>

<style>
html {
	overflow-y: auto !important;
}

.v-parallax {
	height: calc(100vh - (48px + 16px * 3)) !important;
	margin-bottom: 16px;
}

.v-parallax .v-btn {
	position: absolute;
	bottom: 48px;
}

.v-parallax .v-img__img {
	object-fit: cover;
}
</style>