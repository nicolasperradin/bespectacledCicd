<template>
  <Toolbar
    :actions="['delete']"
    :breadcrumb="breadcrumb"
    :is-loading="isLoading"
    @delete="deleteItem"
  />

  <v-container fluid>
    <v-alert v-if="error || deleteError" type="error" class="mb-4" closable>
      {{ error || deleteError }}
    </v-alert>

    <v-table v-if="item">
      <thead>
        <tr>
          <th>{{ $t("field") }}</th>
          <th>{{ $t("value") }}</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>
            {{ $t("event.name") }}
          </td>

          <td>
            {{ item.name }}
                      </td>
        </tr>
        <tr>
          <td>
            {{ $t("event.room") }}
          </td>

          <td>
            <router-link
              v-if="router.hasRoute('RoomShow')"
              :to="{ name: 'RoomShow', params: { id: item.room } }"
            >
              {{ item.room }}
            </router-link>

            <p v-else>
              {{ item.room }}
            </p>
          </td>
        </tr>
        <tr>
          <td>
            {{ $t("event.artists") }}
          </td>

          <td>
            <template v-if="router.hasRoute('UserShow')">
              <router-link
                v-for="user in item.users"
                :to="{ name: 'UserShow', params: { id: user } }"
                :key="user"
              >
                {{ user }}

                <br />
              </router-link>
            </template>

            <template v-else>
              <p
                v-for="user in item.users"
                :key="user"
              >
                {{ user }}
              </p>
            </template>
          </td>
        </tr>
        <tr>
          <td>
            {{ $t("event.price") }}
          </td>

          <td>
            {{ item.price }}
                      </td>
        </tr>
        <tr>
          <td>
            {{ $t("event.schedules") }}
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

<script setup lang="ts">
import { onBeforeUnmount } from "vue";
import { useI18n } from "vue-i18n";
import { useRoute, useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import Toolbar from "@/components/common/Toolbar.vue";
import Loading from "@/components/common/Loading.vue";
import { useMercureItem } from "@/composables/mercureItem";
import { useEventDeleteStore } from "@/store/event/delete";
import { useEventShowStore } from "@/store/event/show";
import { useBreadcrumb } from "@/composables/breadcrumb";

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const eventShowStore = useEventShowStore();
const { retrieved: item, isLoading, error } = storeToRefs(eventShowStore);

const eventDeleteStore = useEventDeleteStore();
const { deleted, error: deleteError } = storeToRefs(eventDeleteStore);

useMercureItem({
  store: eventShowStore,
  deleteStore: eventDeleteStore,
  redirectRouteName: "EventList",
});

await eventShowStore.retrieve(decodeURIComponent(route.params.id as string));

async function deleteItem() {
  if (!item?.value) {
    eventDeleteStore.setError(t("itemNotFound"));
    return;
  }

  await eventDeleteStore.deleteItem(item.value);

  if (!deleted?.value) {
    return;
  }

  router.push({ name: "EventList" });
}

onBeforeUnmount(() => {
  eventShowStore.$reset();
});
</script>
