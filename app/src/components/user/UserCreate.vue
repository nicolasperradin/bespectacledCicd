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
import Form from "@/components/user/UserForm.vue";
import { useUserCreateStore } from "@/store/user/create";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { User } from "@/types/user";

const router = useRouter();
const breadcrumb = useBreadcrumb();

const userCreateStore = useUserCreateStore();
const { created, isLoading, violations, error } = storeToRefs(userCreateStore);

async function create(item: User) {
  await userCreateStore.create(item);

  if (!created?.value) {
    return;
  }

  router.push({ name: "UserUpdate", params: { id: created?.value?.["@id"] } });
}

onBeforeUnmount(() => {
  userCreateStore.$reset();
});
</script>
