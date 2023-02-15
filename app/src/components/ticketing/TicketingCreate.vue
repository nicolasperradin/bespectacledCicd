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
import Form from "@/components/ticketing/TicketingForm.vue";
import { useTicketingCreateStore } from "@/store/ticketing/create";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { Ticketing } from "@/types/ticketing";

const router = useRouter();
const breadcrumb = useBreadcrumb();

const ticketingCreateStore = useTicketingCreateStore();
const { created, isLoading, violations, error } = storeToRefs(ticketingCreateStore);

async function create(item: Ticketing) {
  await ticketingCreateStore.create(item);

  if (!created?.value) {
    return;
  }

  router.push({ name: "TicketingUpdate", params: { id: created?.value?.["@id"] } });
}

onBeforeUnmount(() => {
  ticketingCreateStore.$reset();
});
</script>
