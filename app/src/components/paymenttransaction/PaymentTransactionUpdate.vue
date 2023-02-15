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
import Form from "@/components/paymenttransaction/PaymentTransactionForm.vue";
import Loading from "@/components/common/Loading.vue";
import { usePaymentTransactionDeleteStore } from "@/store/paymenttransaction/delete";
import { usePaymentTransactionUpdateStore } from "@/store/paymenttransaction/update";
import { useMercureItem } from "@/composables/mercureItem";
import { usePaymentTransactionCreateStore } from "@/store/paymenttransaction/create";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { PaymentTransaction } from "@/types/paymenttransaction";

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const paymenttransactionCreateStore = usePaymentTransactionCreateStore();
const { created } = storeToRefs(paymenttransactionCreateStore);

const paymenttransactionDeleteStore = usePaymentTransactionDeleteStore();
const { isLoading: deleteLoading, error: deleteError } =
  storeToRefs(paymenttransactionDeleteStore);

const paymenttransactionUpdateStore = usePaymentTransactionUpdateStore();
const {
  retrieved: item,
  updated,
  isLoading,
  error,
  violations,
} = storeToRefs(paymenttransactionUpdateStore);

useMercureItem({
  store: paymenttransactionUpdateStore,
  deleteStore: paymenttransactionDeleteStore,
  redirectRouteName: "PaymentTransactionList",
});

await paymenttransactionUpdateStore.retrieve(decodeURIComponent(route.params.id as string));

async function update(item: PaymentTransaction) {
  await paymenttransactionUpdateStore.update(item);
}

async function deleteItem() {
  if (!item?.value) {
    paymenttransactionUpdateStore.setError(t("itemNotFound"));
    return;
  }

  await paymenttransactionDeleteStore.deleteItem(item?.value);

  router.push({ name: "PaymentTransactionList" });
}

onBeforeUnmount(() => {
  paymenttransactionUpdateStore.$reset();
  paymenttransactionCreateStore.$reset();
  paymenttransactionDeleteStore.$reset();
});
</script>
