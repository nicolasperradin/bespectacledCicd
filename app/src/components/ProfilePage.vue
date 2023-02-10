<script setup>
import { onMounted, reactive, ref, watch } from 'vue'

import UserService from '../services/user.service'

const message = ref('')
const inputs = reactive({ id: 0, username: '', email: '', roles: [], token: '' })

// watch(message, val => val && setTimeout(() => message.value = '', 3000))
watch(message, val => console.log(val))
watch(inputs, val => console.log('inputs', val))

onMounted(() => {
	console.log('Mounting profile page...')
	UserService.getProfile().then(
		res => {
			// inputs = { ...res.data, token: JSON.parse(localStorage.getItem('user')).token }
			inputs.id = res.data.id
			inputs.username = res.data.username
			inputs.email = res.data.email
			inputs.roles = res.data.roles
			inputs.token = JSON.parse(localStorage.getItem('user')).token
		}, err => {
			message.value = err?.response?.data?.message || err.toString()
		}
	)

	/* UserService.getProfile().then(
		response => {
			this.id = response.data.id;
			this.username = response.data.username;
			this.email = response.data.email;
			this.roles = response.data.roles;
			this.token = JSON.parse(localStorage.getItem('user')).token;
		},
		error => {
			this.content =
				(error.response && error.response.data) ||
				error.message ||
				error.toString();
		}
	); */
});
</script>

<template>
	<div class="container">
		<header class="jumbotron">
			<h3>
				<strong>{{ inputs.username }}</strong> Profile
			</h3>
		</header>
		<p>
			<strong>Token:</strong>
			{{ inputs.token.substring(0, 20) }} ...
			{{ inputs.token.substr(inputs.token.length - 20) }}
		</p>
		<p>
			<strong>Id:</strong>
			{{ inputs.id }}
		</p>
		<p>
			<strong>Username:</strong>
			{{ inputs.username }}
		</p>
		<p>
			<strong>Email:</strong>
			{{ inputs.email }}
		</p>
		<strong>Roles:</strong>
		<ul>
			<li v-for="role in inputs.roles" :key="role">{{ role }}</li>
		</ul>
	</div>
</template>