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
            {{ $t("roomreservation.created") }}
          </td>

          <td>
            {{ formatDateTime(item.created) }}
          </td>
        </tr>
        <tr>
          <td>
            {{ $t("roomreservation.roomId") }}
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
            {{ $t("roomreservation.client") }}
          </td>

          <td>
            <router-link
              v-if="router.hasRoute('UserShow')"
              :to="{ name: 'UserShow', params: { id: item.user } }"
            >
              {{ item.user }}
            </router-link>

            <p v-else>
              {{ item.user }}
            </p>
          </td>
        </tr>
        <tr>
          <td>
            {{ $t("roomreservation.status") }}
          </td>

          <td>
            {{ item.status }}
                      </td>
        </tr>
        <tr>
          <td>
            {{ $t("roomreservation.paymentTransaction") }}
          </td>

          <td>
            <router-link
              v-if="router.hasRoute('PaymentTransactionShow')"
              :to="{ name: 'PaymentTransactionShow', params: { id: item.paymenttransaction } }"
            >
              {{ item.paymenttransaction }}
            </router-link>

            <p v-else>
              {{ item.paymenttransaction }}
            </p>
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
import { useRoomReservationDeleteStore } from "@/store/roomreservation/delete";
import { useRoomReservationShowStore } from "@/store/roomreservation/show";
import { formatDateTime } from "@/utils/date";
import { useBreadcrumb } from "@/composables/breadcrumb";

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const roomreservationShowStore = useRoomReservationShowStore();
const { retrieved: item, isLoading, error } = storeToRefs(roomreservationShowStore);

const roomreservationDeleteStore = useRoomReservationDeleteStore();
const { deleted, error: deleteError } = storeToRefs(roomreservationDeleteStore);

useMercureItem({
  store: roomreservationShowStore,
  deleteStore: roomreservationDeleteStore,
  redirectRouteName: "RoomReservationList",
});

await roomreservationShowStore.retrieve(decodeURIComponent(route.params.id as string));

async function deleteItem() {
  if (!item?.value) {
    roomreservationDeleteStore.setError(t("itemNotFound"));
    return;
  }

  await roomreservationDeleteStore.deleteItem(item.value);

  if (!deleted?.value) {
    return;
  }

  router.push({ name: "RoomReservationList" });
}

onBeforeUnmount(() => {
  roomreservationShowStore.$reset();
});
</script>
