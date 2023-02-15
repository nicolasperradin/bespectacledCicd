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
import Form from "@/components/ticketing/TicketingForm.vue";
import Loading from "@/components/common/Loading.vue";
import { useTicketingDeleteStore } from "@/store/ticketing/delete";
import { useTicketingUpdateStore } from "@/store/ticketing/update";
import { useMercureItem } from "@/composables/mercureItem";
import { useTicketingCreateStore } from "@/store/ticketing/create";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { Ticketing } from "@/types/ticketing";

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const ticketingCreateStore = useTicketingCreateStore();
const { created } = storeToRefs(ticketingCreateStore);

const ticketingDeleteStore = useTicketingDeleteStore();
const { isLoading: deleteLoading, error: deleteError } =
  storeToRefs(ticketingDeleteStore);

const ticketingUpdateStore = useTicketingUpdateStore();
const {
  retrieved: item,
  updated,
  isLoading,
  error,
  violations,
} = storeToRefs(ticketingUpdateStore);

useMercureItem({
  store: ticketingUpdateStore,
  deleteStore: ticketingDeleteStore,
  redirectRouteName: "TicketingList",
});

await ticketingUpdateStore.retrieve(decodeURIComponent(route.params.id as string));

async function update(item: Ticketing) {
  await ticketingUpdateStore.update(item);
}

async function deleteItem() {
  if (!item?.value) {
    ticketingUpdateStore.setError(t("itemNotFound"));
    return;
  }

  await ticketingDeleteStore.deleteItem(item?.value);

  router.push({ name: "TicketingList" });
}

onBeforeUnmount(() => {
  ticketingUpdateStore.$reset();
  ticketingCreateStore.$reset();
  ticketingDeleteStore.$reset();
});
</script>
