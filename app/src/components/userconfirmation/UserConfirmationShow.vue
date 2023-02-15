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

    <v-table v-if="item">
      <thead>
        <tr>
          <th>{{ $t("field") }}</th>
          <th>{{ $t("value") }}</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>
            {{ $t("userconfirmation.confirmationToken") }}
          </td>

          <td>
            {{ item.confirmationToken }}
                      </td>
        </tr>
      </tbody>
    </v-table>
  </v-container>

  <Loading :visible="isLoading" />
</template>

<script setup lang="ts">
import { onBeforeUnmount } from "vue";
import { useI18n } from "vue-i18n";
import { useRoute, useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import Toolbar from "@/components/common/Toolbar.vue";
import Loading from "@/components/common/Loading.vue";
import { useMercureItem } from "@/composables/mercureItem";
import { useUserConfirmationDeleteStore } from "@/store/userconfirmation/delete";
import { useUserConfirmationShowStore } from "@/store/userconfirmation/show";
import { useBreadcrumb } from "@/composables/breadcrumb";

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const userconfirmationShowStore = useUserConfirmationShowStore();
const { retrieved: item, isLoading, error } = storeToRefs(userconfirmationShowStore);

const userconfirmationDeleteStore = useUserConfirmationDeleteStore();
const { deleted, error: deleteError } = storeToRefs(userconfirmationDeleteStore);

useMercureItem({
  store: userconfirmationShowStore,
  deleteStore: userconfirmationDeleteStore,
  redirectRouteName: "UserConfirmationList",
});

await userconfirmationShowStore.retrieve(decodeURIComponent(route.params.id as string));

async function deleteItem() {
  if (!item?.value) {
    userconfirmationDeleteStore.setError(t("itemNotFound"));
    return;
  }

  await userconfirmationDeleteStore.deleteItem(item.value);

  if (!deleted?.value) {
    return;
  }

  router.push({ name: "UserConfirmationList" });
}

onBeforeUnmount(() => {
  userconfirmationShowStore.$reset();
});
</script>
