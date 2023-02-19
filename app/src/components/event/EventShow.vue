<script setup lang="ts">
import { onBeforeUnmount } from 'vue'
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
					<td>
						{{ $t('event.title') }}
					</td>

					<td>
						{{ item.title }}
					</td>
				</tr>

				<tr>
					<td>
						{{ $t('event.type') }}
					</td>

					<td>
						{{ item.type }}
					</td>
				</tr>

				<tr>
					<td>
						{{ $t('event.price') }}
					</td>

					<td>
						{{ item.price }}
					</td>
				</tr>

				<tr>
					<td>
						{{ $t('event.src') }}
					</td>

					<td>
						{{ item.src }}
					</td>
				</tr>

				<tr>
					<td>
						{{ $t('event.venue') }}
					</td>

					<td>
						<router-link v-if="router.hasRoute('VenueShow')" :to="{ name: 'VenueShow', params: { id: item.venue } }">
							{{ item.venue }}
						</router-link>

						<p v-else>
							{{ item.venue }}
						</p>
					</td>
				</tr>

				<tr>
					<td>
						{{ $t('event.artists') }}
					</td>
'
					<td>
						<template v-if="router.hasRoute('UserShow')">
							<router-link v-for="user in item.artists" :key="user" :to="{ name: 'UserShow', params: { id: user } }">
								{{ user }}
								<br />
							</router-link>
						</template>

						<template v-else>
							<p v-for="user in item.artists" :key="user">
								{{ user }}
							</p>
						</template>
					</td>
				</tr>

				<!-- TODO should be like above -->
				<tr>
					<td>
						{{ $t('event.schedules') }}
					</td>

					<td>
						{{ item.schedules }}
					</td>
				</tr>
			</tbody>
		</v-table>
	</v-container>

	<Loading :visible="isLoading" />
</template>