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
import Form from "@/components/room/RoomForm.vue";
import Loading from "@/components/common/Loading.vue";
import { useRoomDeleteStore } from "@/store/room/delete";
import { useRoomUpdateStore } from "@/store/room/update";
import { useMercureItem } from "@/composables/mercureItem";
import { useRoomCreateStore } from "@/store/room/create";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { Room } from "@/types/room";

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const roomCreateStore = useRoomCreateStore();
const { created } = storeToRefs(roomCreateStore);

const roomDeleteStore = useRoomDeleteStore();
const { isLoading: deleteLoading, error: deleteError } =
  storeToRefs(roomDeleteStore);

const roomUpdateStore = useRoomUpdateStore();
const {
  retrieved: item,
  updated,
  isLoading,
  error,
  violations,
} = storeToRefs(roomUpdateStore);

useMercureItem({
  store: roomUpdateStore,
  deleteStore: roomDeleteStore,
  redirectRouteName: "RoomList",
});

await roomUpdateStore.retrieve(decodeURIComponent(route.params.id as string));

async function update(item: Room) {
  await roomUpdateStore.update(item);
}

async function deleteItem() {
  if (!item?.value) {
    roomUpdateStore.setError(t("itemNotFound"));
    return;
  }

  await roomDeleteStore.deleteItem(item?.value);

  router.push({ name: "RoomList" });
}

onBeforeUnmount(() => {
  roomUpdateStore.$reset();
  roomCreateStore.$reset();
  roomDeleteStore.$reset();
});
</script>
