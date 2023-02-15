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
import Form from "@/components/userconfirmation/UserConfirmationForm.vue";
import Loading from "@/components/common/Loading.vue";
import { useUserConfirmationDeleteStore } from "@/store/userconfirmation/delete";
import { useUserConfirmationUpdateStore } from "@/store/userconfirmation/update";
import { useMercureItem } from "@/composables/mercureItem";
import { useUserConfirmationCreateStore } from "@/store/userconfirmation/create";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { UserConfirmation } from "@/types/userconfirmation";

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const userconfirmationCreateStore = useUserConfirmationCreateStore();
const { created } = storeToRefs(userconfirmationCreateStore);

const userconfirmationDeleteStore = useUserConfirmationDeleteStore();
const { isLoading: deleteLoading, error: deleteError } =
  storeToRefs(userconfirmationDeleteStore);

const userconfirmationUpdateStore = useUserConfirmationUpdateStore();
const {
  retrieved: item,
  updated,
  isLoading,
  error,
  violations,
} = storeToRefs(userconfirmationUpdateStore);

useMercureItem({
  store: userconfirmationUpdateStore,
  deleteStore: userconfirmationDeleteStore,
  redirectRouteName: "UserConfirmationList",
});

await userconfirmationUpdateStore.retrieve(decodeURIComponent(route.params.id as string));

async function update(item: UserConfirmation) {
  await userconfirmationUpdateStore.update(item);
}

async function deleteItem() {
  if (!item?.value) {
    userconfirmationUpdateStore.setError(t("itemNotFound"));
    return;
  }

  await userconfirmationDeleteStore.deleteItem(item?.value);

  router.push({ name: "UserConfirmationList" });
}

onBeforeUnmount(() => {
  userconfirmationUpdateStore.$reset();
  userconfirmationCreateStore.$reset();
  userconfirmationDeleteStore.$reset();
});
</script>
