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

    <v-alert v-if="created || updated" type="success" class="mb-4" closable>
      <template v-if="updated">
        {{ $t("itemUpdated", [updated["@id"]]) }}
      </template>
      <template v-else-if="created">
        {{ $t("itemCreated", [created["@id"]]) }}
      </template>
    </v-alert>

    <Form v-if="item" :values="item" :errors="violations" @submit="update" />
  </v-container>

  <Loading :visible="isLoading || deleteLoading" />
</template>

<script lang="ts" setup>
import { onBeforeUnmount } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
import { storeToRefs } from "pinia";
import Toolbar from "@/components/common/Toolbar.vue";
import Form from "@/components/roomreservation/RoomReservationForm.vue";
import Loading from "@/components/common/Loading.vue";
import { useRoomReservationDeleteStore } from "@/store/roomreservation/delete";
import { useRoomReservationUpdateStore } from "@/store/roomreservation/update";
import { useMercureItem } from "@/composables/mercureItem";
import { useRoomReservationCreateStore } from "@/store/roomreservation/create";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { RoomReservation } from "@/types/roomreservation";

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const roomreservationCreateStore = useRoomReservationCreateStore();
const { created } = storeToRefs(roomreservationCreateStore);

const roomreservationDeleteStore = useRoomReservationDeleteStore();
const { isLoading: deleteLoading, error: deleteError } =
  storeToRefs(roomreservationDeleteStore);

const roomreservationUpdateStore = useRoomReservationUpdateStore();
const {
  retrieved: item,
  updated,
  isLoading,
  error,
  violations,
} = storeToRefs(roomreservationUpdateStore);

useMercureItem({
  store: roomreservationUpdateStore,
  deleteStore: roomreservationDeleteStore,
  redirectRouteName: "RoomReservationList",
});

await roomreservationUpdateStore.retrieve(decodeURIComponent(route.params.id as string));

async function update(item: RoomReservation) {
  await roomreservationUpdateStore.update(item);
}

async function deleteItem() {
  if (!item?.value) {
    roomreservationUpdateStore.setError(t("itemNotFound"));
    return;
  }

  await roomreservationDeleteStore.deleteItem(item?.value);

  router.push({ name: "RoomReservationList" });
}

onBeforeUnmount(() => {
  roomreservationUpdateStore.$reset();
  roomreservationCreateStore.$reset();
  roomreservationDeleteStore.$reset();
});
</script>
