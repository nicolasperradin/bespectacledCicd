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
import Form from "@/components/event/EventForm.vue";
import Loading from "@/components/common/Loading.vue";
import { useEventDeleteStore } from "@/store/event/delete";
import { useEventUpdateStore } from "@/store/event/update";
import { useMercureItem } from "@/composables/mercureItem";
import { useEventCreateStore } from "@/store/event/create";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { Event } from "@/types/event";

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const eventCreateStore = useEventCreateStore();
const { created } = storeToRefs(eventCreateStore);

const eventDeleteStore = useEventDeleteStore();
const { isLoading: deleteLoading, error: deleteError } =
  storeToRefs(eventDeleteStore);

const eventUpdateStore = useEventUpdateStore();
const {
  retrieved: item,
  updated,
  isLoading,
  error,
  violations,
} = storeToRefs(eventUpdateStore);

useMercureItem({
  store: eventUpdateStore,
  deleteStore: eventDeleteStore,
  redirectRouteName: "EventList",
});

await eventUpdateStore.retrieve(decodeURIComponent(route.params.id as string));

async function update(item: Event) {
  await eventUpdateStore.update(item);
}

async function deleteItem() {
  if (!item?.value) {
    eventUpdateStore.setError(t("itemNotFound"));
    return;
  }

  await eventDeleteStore.deleteItem(item?.value);

  router.push({ name: "EventList" });
}

onBeforeUnmount(() => {
  eventUpdateStore.$reset();
  eventCreateStore.$reset();
  eventDeleteStore.$reset();
});
</script>
