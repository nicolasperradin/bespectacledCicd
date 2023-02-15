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
import Form from "@/components/userconfirmation/UserConfirmationForm.vue";
import { useUserConfirmationCreateStore } from "@/store/userconfirmation/create";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { UserConfirmation } from "@/types/userconfirmation";

const router = useRouter();
const breadcrumb = useBreadcrumb();

const userconfirmationCreateStore = useUserConfirmationCreateStore();
const { created, isLoading, violations, error } = storeToRefs(userconfirmationCreateStore);

async function create(item: UserConfirmation) {
  await userconfirmationCreateStore.create(item);

  if (!created?.value) {
    return;
  }

  router.push({ name: "UserConfirmationUpdate", params: { id: created?.value?.["@id"] } });
}

onBeforeUnmount(() => {
  userconfirmationCreateStore.$reset();
});
</script>
