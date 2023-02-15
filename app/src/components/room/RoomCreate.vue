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
import Form from "@/components/room/RoomForm.vue";
import { useRoomCreateStore } from "@/store/room/create";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { Room } from "@/types/room";

const router = useRouter();
const breadcrumb = useBreadcrumb();

const roomCreateStore = useRoomCreateStore();
const { created, isLoading, violations, error } = storeToRefs(roomCreateStore);

async function create(item: Room) {
  await roomCreateStore.create(item);

  if (!created?.value) {
    return;
  }

  router.push({ name: "RoomUpdate", params: { id: created?.value?.["@id"] } });
}

onBeforeUnmount(() => {
  roomCreateStore.$reset();
});
</script>
