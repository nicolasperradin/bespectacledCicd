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
          :to="{ name: 'TicketingShow', params: { id: item.raw['@id'] } }"
        >
          {{ item.raw["@id"] }}
        </router-link>
      </template>

      <template #item.user="{ item }">
        <router-link
          v-if="router.hasRoute('UserShow')"
          :to="{ name: 'UserShow', params: { id: item.raw.user } }"
        >
          {{ item.raw.user }}
        </router-link>

        <p v-else>
          {{ item.raw.user }}
        </p>
      </template>
      <template #item.event="{ item }">
        <router-link
          v-if="router.hasRoute('EventShow')"
          :to="{ name: 'EventShow', params: { id: item.raw.event } }"
        >
          {{ item.raw.event }}
        </router-link>

        <p v-else>
          {{ item.raw.event }}
        </p>
      </template>
      <template #item.paymenttransaction="{ item }">
        <router-link
          v-if="router.hasRoute('PaymentTransactionShow')"
          :to="{ name: 'PaymentTransactionShow', params: { id: item.raw.paymenttransaction } }"
        >
          {{ item.raw.paymenttransaction }}
        </router-link>

        <p v-else>
          {{ item.raw.paymenttransaction }}
        </p>
      </template>
    </v-data-table-server>
  </v-container>
</template>

<script setup lang="ts">
import { ref, onBeforeUnmount } from "vue";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import { useTicketingListStore } from "@/store/ticketing/list";
import { useTicketingDeleteStore } from "@/store/ticketing/delete";
import Toolbar from "@/components/common/Toolbar.vue";
import ActionCell from "@/components/common/ActionCell.vue";
import { useMercureList } from "@/composables/mercureList";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { VuetifyOrder } from "@/types/list";
import type { Ticketing } from "@/types/ticketing";

const { t } = useI18n();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const ticketingDeleteStore = useTicketingDeleteStore();
const { deleted, mercureDeleted } = storeToRefs(ticketingDeleteStore);

const ticketingListStore = useTicketingListStore();
const { items, totalItems, error, isLoading } = storeToRefs(ticketingListStore);

const page = ref("1");
const order = ref({});

async function sendRequest() {
  await ticketingListStore.getItems({
    page: page.value,
    order: order.value,
  });
}

useMercureList({ store: ticketingListStore, deleteStore: ticketingDeleteStore });

sendRequest();

const headers = [
  {
    title: t("actions"),
    key: "actions",
    sortable: false,
  },
  { title: t("id"), key: "@id" },
  {
    title: t("ticketing.buyer"),
    key: "buyer",
    sortable: false,
  },
  {
    title: t("ticketing.event"),
    key: "event",
    sortable: false,
  },
  {
    title: t("ticketing.status"),
    key: "status",
    sortable: false,
  },
  {
    title: t("ticketing.paymentTransaction"),
    key: "paymentTransaction",
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


function goToShowPage(item: Ticketing) {
  router.push({
    name: "TicketingShow",
    params: { id: item["@id"] },
  });
}

function goToCreatePage() {
  router.push({
    name: "TicketingCreate",
  });
}

function goToUpdatePage(item: Ticketing) {
  router.push({
    name: "TicketingUpdate",
    params: { id: item["@id"] },
  });
}

async function deleteItem(item: Ticketing) {
  await ticketingDeleteStore.deleteItem(item);

  sendRequest();
}

onBeforeUnmount(() => {
  ticketingDeleteStore.$reset();
});
</script>
