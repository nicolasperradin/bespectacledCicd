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
            {{ $t("room.name") }}
          </td>

          <td>
            {{ item.name }}
                      </td>
        </tr>
        <tr>
          <td>
            {{ $t("room.nbSeats") }}
          </td>

          <td>
            {{ item.nbSeats }}
                      </td>
        </tr>
        <tr>
          <td>
            {{ $t("room.price") }}
          </td>

          <td>
            {{ item.price }}
                      </td>
        </tr>
        <tr>
          <td>
            {{ $t("room.events") }}
          </td>

          <td>
            <template v-if="router.hasRoute('EventShow')">
              <router-link
                v-for="event in item.events"
                :to="{ name: 'EventShow', params: { id: event } }"
                :key="event"
              >
                {{ event }}

                <br />
              </router-link>
            </template>

            <template v-else>
              <p
                v-for="event in item.events"
                :key="event"
              >
                {{ event }}
              </p>
            </template>
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
import { useRoomDeleteStore } from "@/store/room/delete";
import { useRoomShowStore } from "@/store/room/show";
import { useBreadcrumb } from "@/composables/breadcrumb";

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const roomShowStore = useRoomShowStore();
const { retrieved: item, isLoading, error } = storeToRefs(roomShowStore);

const roomDeleteStore = useRoomDeleteStore();
const { deleted, error: deleteError } = storeToRefs(roomDeleteStore);

useMercureItem({
  store: roomShowStore,
  deleteStore: roomDeleteStore,
  redirectRouteName: "RoomList",
});

await roomShowStore.retrieve(decodeURIComponent(route.params.id as string));

async function deleteItem() {
  if (!item?.value) {
    roomDeleteStore.setError(t("itemNotFound"));
    return;
  }

  await roomDeleteStore.deleteItem(item.value);

  if (!deleted?.value) {
    return;
  }

  router.push({ name: "RoomList" });
}

onBeforeUnmount(() => {
  roomShowStore.$reset();
});
</script>
