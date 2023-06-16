<script setup>
import { ref, onBeforeMount } from 'vue'

const data = ref(null)

onBeforeMount(() => {
	const name = 'Jimi Hendrix'
	const url = 'https://en.wikipedia.org/w/api.php?action=parse&format=json&prop=text&section=0&page=' + name + '&callback=?'

	// Add Access-Control-Allow-Origin: * to the header
	// fetch(url, { mode: 'no-cors' })
	// 	.then(res => res.json())
	// 	.catch(err => console.log(err))
	// 	.then(res => data.value = res.parse.text['*'])
})
</script>

<template>
	<div v-html="data" />
</template>