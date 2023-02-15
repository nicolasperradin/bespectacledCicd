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
            {{ $t("paymenttransaction.amount") }}
          </td>

          <td>
            {{ item.amount }}
                      </td>
        </tr>
        <tr>
          <td>
            {{ $t("paymenttransaction.status") }}
          </td>

          <td>
            {{ item.status }}
                      </td>
        </tr>
        <tr>
          <td>
            {{ $t("paymenttransaction.createdAt") }}
          </td>

          <td>
            {{ formatDateTime(item.createdAt) }}
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
import { usePaymentTransactionDeleteStore } from "@/store/paymenttransaction/delete";
import { usePaymentTransactionShowStore } from "@/store/paymenttransaction/show";
import { formatDateTime } from "@/utils/date";
import { useBreadcrumb } from "@/composables/breadcrumb";

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const paymenttransactionShowStore = usePaymentTransactionShowStore();
const { retrieved: item, isLoading, error } = storeToRefs(paymenttransactionShowStore);

const paymenttransactionDeleteStore = usePaymentTransactionDeleteStore();
const { deleted, error: deleteError } = storeToRefs(paymenttransactionDeleteStore);

useMercureItem({
  store: paymenttransactionShowStore,
  deleteStore: paymenttransactionDeleteStore,
  redirectRouteName: "PaymentTransactionList",
});

await paymenttransactionShowStore.retrieve(decodeURIComponent(route.params.id as string));

async function deleteItem() {
  if (!item?.value) {
    paymenttransactionDeleteStore.setError(t("itemNotFound"));
    return;
  }

  await paymenttransactionDeleteStore.deleteItem(item.value);

  if (!deleted?.value) {
    return;
  }

  router.push({ name: "PaymentTransactionList" });
}

onBeforeUnmount(() => {
  paymenttransactionShowStore.$reset();
});
</script>
