<template>
  <Toolbar :breadcrumb="breadcrumb" :is-loading="isLoading" />

  <v-container fluid>
    <v-alert v-if="error" type="error" class="mb-4" closable>{{ error }}</v-alert>

    <Form :errors="violations" @submit="create" />
  </v-container>

  <Loading :visible="isLoading" />
</template>

<script setup lang="ts">
import { onBeforeUnmount } from "vue";
import { useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import Toolbar from "@/components/common/Toolbar.vue";
import Loading from "@/components/common/Loading.vue";
import Form from "@/components/roomreservation/RoomReservationForm.vue";
import { useRoomReservationCreateStore } from "@/store/roomreservation/create";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { RoomReservation } from "@/types/roomreservation";

const router = useRouter();
const breadcrumb = useBreadcrumb();

const roomreservationCreateStore = useRoomReservationCreateStore();
const { created, isLoading, violations, error } = storeToRefs(roomreservationCreateStore);

async function create(item: RoomReservation) {
  await roomreservationCreateStore.create(item);

  if (!created?.value) {
    return;
  }

  router.push({ name: "RoomReservationUpdate", params: { id: created?.value?.["@id"] } });
}

onBeforeUnmount(() => {
  roomreservationCreateStore.$reset();
});
</script>
