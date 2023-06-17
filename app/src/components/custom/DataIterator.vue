<script setup lang="ts">
import { computed, onBeforeUnmount, ref, toRefs } from 'vue'
import { useI18n } from 'vue-i18n'
import { storeToRefs } from 'pinia'
import { useRouter } from 'vue-router'

const props = defineProps<{
	items: any[]
	keys: string[]
	isLoading: boolean
	page?: number
	sortKey?: string
	sortOrder?: string
	itemsPerPage?: number
	availability?: (item: any) => boolean
}>()


const { items } = toRefs(props)

const search = ref('')
const page = ref(props.page ?? 1)
const sortOrder = ref(props.sortOrder ?? 'desc')
const itemsPerPage = ref(props.itemsPerPage ?? 6)

const showOnlyAvailable = ref(false)
const itemsPerPageArray = [6, 12, 18, 24, 30]
const sortKey = ref(props.sortKey ?? props.keys[0])
const availability = props.availability ?? (() => true)
const sortBy = computed<any>(() => [{ key: sortKey.value, order: sortOrder.value }])
const numberOfPages = computed(() => Math.ceil(items.value.length / itemsPerPage.value))
const filteredItems = computed(() => showOnlyAvailable.value ? items.value.filter(availability) : items.value)

// const parallax = new URL('@/assets/stadium.jpeg', import.meta.url).href

const onIntersect = {
	handler: (b: any, e: any) => {
		e[0].target.style.transition = 'opacity .3s ease'
		e[0].target.style.opacity = e[0].intersectionRatio * 2
	},
	options: { threshold: [0, .25, .5, .75, 1] }
}

const prevPage = () => page.value - 1 >= 1 && (page.value -= 1)
const nextPage = () => page.value + 1 <= numberOfPages.value && (page.value += 1)
</script>

<template>
	<v-data-iterator
		v-model:items-per-page="itemsPerPage"
		v-model:page="page"
		:items="filteredItems"
		:search="search"
		:sort-by="sortBy"
	>
		<template #header>
			<v-toolbar class="px-2 mb-4 sticky-top sticky-nav" color="primary" dark rounded>
				<v-text-field v-model="search" clearable hide-details prepend-inner-icon="fa fa-magnifying-glass" placeholder="Search" variant="solo" density="comfortable" />

				<v-spacer />

				<v-checkbox v-model="showOnlyAvailable" class="m-0" label="Show only available" density="comfortable" hide-details />
				<v-select v-model="sortKey" :items="keys" :item-value="item => item.toLowerCase()" prepend-inner-icon="fa fa-sort" label="Sort by" density="comfortable" hide-details />

				<v-spacer />

				<v-btn-toggle v-model="sortOrder" mandatory>
					<v-btn icon="fa fa-sort-alpha-asc" color="blue" value="asc" />
					<v-btn icon="fa fa-sort-alpha-desc" color="blue" value="desc" />
				</v-btn-toggle>
			</v-toolbar>
		</template>

		<template #no-data>
			<v-row v-if="isLoading" class="mb-6" justify="space-around">
				<v-col v-for="n in 6" :key="n" class="snap" v-intersect="onIntersect">
					<slot name="skeleton">
						<v-skeleton-loader class="mx-auto align-content-start" width="350" height="492" elevation="8" type="image,image,heading,chip@3,divider,actions" />
					</slot>
				</v-col>
			</v-row>

			<v-alert v-if="!isLoading" type="warning" text="No results found" />
		</template>

		<template #default="props">
			<v-row justify="space-around">
				<slot v-bind="props" :onIntersect="onIntersect"></slot>
			</v-row>
		</template>

		<template #footer>
			<!-- <v-bottom-sheet open-on-hover> -->
				<div class="d-flex align-center justify-space-around pa-4 snap">
					<span class="grey--text">Items per page</span>

					<v-menu>
						<template #activator="{ props }">
							<v-btn v-bind="props" class="ml-2" color="primary" variant="text" append-icon="fa fa-arrow-down">
								{{ itemsPerPage }}
							</v-btn>
						</template>

						<v-list>
							<v-list-item
								v-for="(number, index) in itemsPerPageArray"
								:key="index"
								:title="number"
								@click="itemsPerPage = number"
							/>
						</v-list>
					</v-menu>

					<v-spacer />

					<span class="mr-4 grey--text">Page {{ page }} of {{ numberOfPages }}</span>
					<v-btn icon="fa fa-chevron-left" size="small" @click="prevPage" />
					<v-btn icon="fa fa-chevron-right" class="ml-2" size="small" @click="nextPage" />
				</div>
			<!-- </v-bottom-sheet> -->
		</template>
	</v-data-iterator>
</template>