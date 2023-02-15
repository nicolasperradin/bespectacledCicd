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
import Form from "@/components/paymenttransaction/PaymentTransactionForm.vue";
import { usePaymentTransactionCreateStore } from "@/store/paymenttransaction/create";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { PaymentTransaction } from "@/types/paymenttransaction";

const router = useRouter();
const breadcrumb = useBreadcrumb();

const paymenttransactionCreateStore = usePaymentTransactionCreateStore();
const { created, isLoading, violations, error } = storeToRefs(paymenttransactionCreateStore);

async function create(item: PaymentTransaction) {
  await paymenttransactionCreateStore.create(item);

  if (!created?.value) {
    return;
  }

  router.push({ name: "PaymentTransactionUpdate", params: { id: created?.value?.["@id"] } });
}

onBeforeUnmount(() => {
  paymenttransactionCreateStore.$reset();
});
</script>
