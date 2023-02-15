<template>
  <Toolbar
    :actions="['add']"
    :breadcrumb="breadcrumb"
    :is-loading="isLoading"
    @add="goToCreatePage"
  />

  <v-container fluid>
    <v-alert v-if="deleted" type="success" class="mb-4" closable>
      {{ $t("itemDeleted", [deleted["@id"]]) }}
    </v-alert>
    <v-alert v-if="mercureDeleted" type="success" class="mb-4" closable>
      {{ $t("itemDeletedByAnotherUser", [mercureDeleted["@id"]]) }}
    </v-alert>

    <v-alert v-if="error" type="error" class="mb-4" closable>
      {{ error }}
    </v-alert>


    <v-data-table-server
      :headers="headers"
      :items="items"
      :items-length="totalItems"
      :loading="isLoading"
      :items-per-page="items.length"
      @update:page="updatePage"
      @update:sortBy="updateOrder"
    >
      <template #item.actions="{ item }">
        <ActionCell
          :actions="['show', 'update', 'delete']"
          @show="goToShowPage(item.raw)"
          @update="goToUpdatePage(item.raw)"
          @delete="deleteItem(item.raw)"
        />
      </template>

      <template #item.@id="{ item }">
        <router-link
          :to="{ name: 'PaymentTransactionShow', params: { id: item.raw['@id'] } }"
        >
          {{ item.raw["@id"] }}
        </router-link>
      </template>

      <template #item.createdAt="{ item }">
        {{ formatDateTime(item.raw.createdAt) }}
      </template>
          </v-data-table-server>
  </v-container>
</template>

<script setup lang="ts">
import { ref, onBeforeUnmount } from "vue";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import { usePaymentTransactionListStore } from "@/store/paymenttransaction/list";
import { usePaymentTransactionDeleteStore } from "@/store/paymenttransaction/delete";
import Toolbar from "@/components/common/Toolbar.vue";
import ActionCell from "@/components/common/ActionCell.vue";
import { formatDateTime } from "@/utils/date";
import { useMercureList } from "@/composables/mercureList";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { VuetifyOrder } from "@/types/list";
import type { PaymentTransaction } from "@/types/paymenttransaction";

const { t } = useI18n();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const paymenttransactionDeleteStore = usePaymentTransactionDeleteStore();
const { deleted, mercureDeleted } = storeToRefs(paymenttransactionDeleteStore);

const paymenttransactionListStore = usePaymentTransactionListStore();
const { items, totalItems, error, isLoading } = storeToRefs(paymenttransactionListStore);

const page = ref("1");
const order = ref({});

async function sendRequest() {
  await paymenttransactionListStore.getItems({
    page: page.value,
    order: order.value,
  });
}

useMercureList({ store: paymenttransactionListStore, deleteStore: paymenttransactionDeleteStore });

sendRequest();

const headers = [
  {
    title: t("actions"),
    key: "actions",
    sortable: false,
  },
  { title: t("id"), key: "@id" },
  {
    title: t("paymenttransaction.amount"),
    key: "amount",
    sortable: false,
  },
  {
    title: t("paymenttransaction.status"),
    key: "status",
    sortable: false,
  },
  {
    title: t("paymenttransaction.createdAt"),
    key: "createdAt",
    sortable: false,
  },
];

function updatePage(newPage: string) {
  page.value = newPage;

  sendRequest();
}

function updateOrder(newOrders: VuetifyOrder[]) {
  const newOrder = newOrders[0];
  order.value = { [newOrder.key]: newOrder.order };

  sendRequest();
}


function goToShowPage(item: PaymentTransaction) {
  router.push({
    name: "PaymentTransactionShow",
    params: { id: item["@id"] },
  });
}

function goToCreatePage() {
  router.push({
    name: "PaymentTransactionCreate",
  });
}

function goToUpdatePage(item: PaymentTransaction) {
  router.push({
    name: "PaymentTransactionUpdate",
    params: { id: item["@id"] },
  });
}

async function deleteItem(item: PaymentTransaction) {
  await paymenttransactionDeleteStore.deleteItem(item);

  sendRequest();
}

onBeforeUnmount(() => {
  paymenttransactionDeleteStore.$reset();
});
</script>
