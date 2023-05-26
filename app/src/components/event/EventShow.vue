<script setup lang="ts">
import { onBeforeUnmount, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { storeToRefs } from 'pinia'
import { useRoute, useRouter } from 'vue-router'

import Toolbar from '@/components/common/Toolbar.vue'
import Loading from '@/components/common/Loading.vue'
import { useEventShowStore } from '@/store/event/show'
import { useBreadcrumb } from '@/composables/breadcrumb'
import { useMercureItem } from '@/composables/mercureItem'
import { useEventDeleteStore } from '@/store/event/delete'

const { t } = useI18n()
const route = useRoute()
const router = useRouter()
const breadcrumb = useBreadcrumb()

const eventShowStore = useEventShowStore()
const { retrieved: item, isLoading, error } = storeToRefs(eventShowStore)

const eventDeleteStore = useEventDeleteStore()
const { deleted, error: deleteError } = storeToRefs(eventDeleteStore)

const icons: { [key: string]: string } = {
	broadway: 'fa fa-mask',
	concert: 'fa fa-microphone',
	other: 'fa fa-question'
}

const tab = ref(1)

useMercureItem({ store: eventShowStore, deleteStore: eventDeleteStore, redirectRouteName: 'EventList' })

await eventShowStore.retrieve(decodeURIComponent(route.params.id as string))

const deleteItem = async () => {
	if (!item?.value) return eventDeleteStore.setError(t('itemNotFound'))
	await eventDeleteStore.deleteItem(item.value)
	if (!deleted?.value) return
	router.push({ name: 'EventList' })
}

onBeforeUnmount(() => eventShowStore.$reset())
</script>

<template>
	<Toolbar :actions="['delete']" :breadcrumb="breadcrumb" :is-loading="isLoading" @delete="deleteItem" />

	<v-container fluid>
		<v-alert v-if="error || deleteError" type="error" class="mb-4" v-text="error || deleteError" closable />

		<v-tabs v-model="tab" color="primary" fixed-tabs>
			<v-tab value="1">General</v-tab>
			<v-tab value="2">Venue</v-tab>
			<v-tab value="3">Artists</v-tab>
			<v-tab value="4">Schedules</v-tab>
		</v-tabs>

		<v-window v-model="tab">
			<v-window-item value="1">
				<!-- TODO replace html tags? -->
				<v-table v-if="item">
					<thead>
						<tr>
							<th>{{ $t('field') }}</th>
							<th>{{ $t('value') }}</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td>{{ $t('event.src') }}</td>
							<td><v-img :src="item.src" max-width="100" max-height="100" /></td>
							<!-- <td>{{ item.src }}</td> -->
						</tr>

						<tr>
							<td>{{ $t('event.title') }}</td>
							<td>{{ item.title }}</td>
						</tr>

						<tr>
							<td>{{ $t('event.type') }}</td>
							<td>{{ item.type }}</td>
						</tr>

						<tr>
							<td>{{ $t('event.price') }}</td>
							<td>{{ item.price }}</td>
						</tr>

						<tr>
							<td>{{ $t('event.venue') }}</td>

							<td>
								<router-link v-if="router.hasRoute('VenueShow')"
									:to="{ name: 'VenueShow', params: { id: item.venue } }">
									<v-img :src="item.venue.src" max-width="100" max-height="100" />
								</router-link>

								<p v-else><v-img :src="item.venue.src" max-width="100" max-height="100" /></p>
							</td>
						</tr>

						<tr>
							<td>
								{{ $t('event.artists') }}
							</td>

							<td>
								<template v-if="router.hasRoute('UserShow')">
									<router-link v-for="artist in item.artists" :key="artist"
										:to="{ name: 'UserShow', params: { id: artist } }">
										<v-chip color="primary" v-text="artist.username" />
										<br />
									</router-link>
								</template>

								<template v-else>
									<v-chip v-for="artist in item.artists" :key="artist" color="primary"
										v-text="artist.username" />
								</template>
							</td>
						</tr>

						<!-- TODO should be like above -->
						<tr>
							<td>
								{{ $t('event.schedules') }}
							</td>

							<td>
								<template v-if="router.hasRoute('ScheduleShow')">
									<router-link v-for="schedule in item.schedules" :key="schedule"
										:to="{ name: 'ScheduleShow', params: { id: schedule } }">
										<v-chip color="primary" v-text="schedule.date" />
										<br />
									</router-link>
								</template>

								<template v-else>
									<!--	<v-chip v-for="schedule in item.schedules" :key="schedule" color="primary" v-text="schedule.date" />-->
								</template>
							</td>
						</tr>
					</tbody>
				</v-table>
			</v-window-item>

			<v-window-item value="2">
				<thead>
					<tr>
						<th>{{ $t('field') }}</th>
						<th>{{ $t('value') }}</th>
					</tr>
				</thead>

				<tbody>
				</tbody>
			</v-window-item>
		</v-window>
	</v-container>

<Loading :visible="isLoading" /></template>