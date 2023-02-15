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
          :to="{ name: 'RoomReservationShow', params: { id: item.raw['@id'] } }"
        >
          {{ item.raw["@id"] }}
        </router-link>
      </template>

      <template #item.created="{ item }">
        {{ formatDateTime(item.raw.created) }}
      </template>
            <template #item.room="{ item }">
        <router-link
          v-if="router.hasRoute('RoomShow')"
          :to="{ name: 'RoomShow', params: { id: item.raw.room } }"
        >
          {{ item.raw.room }}
        </router-link>

        <p v-else>
          {{ item.raw.room }}
        </p>
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
import { useRoomReservationListStore } from "@/store/roomreservation/list";
import { useRoomReservationDeleteStore } from "@/store/roomreservation/delete";
import Toolbar from "@/components/common/Toolbar.vue";
import ActionCell from "@/components/common/ActionCell.vue";
import { formatDateTime } from "@/utils/date";
import { useMercureList } from "@/composables/mercureList";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { VuetifyOrder } from "@/types/list";
import type { RoomReservation } from "@/types/roomreservation";

const { t } = useI18n();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const roomreservationDeleteStore = useRoomReservationDeleteStore();
const { deleted, mercureDeleted } = storeToRefs(roomreservationDeleteStore);

const roomreservationListStore = useRoomReservationListStore();
const { items, totalItems, error, isLoading } = storeToRefs(roomreservationListStore);

const page = ref("1");
const order = ref({});

async function sendRequest() {
  await roomreservationListStore.getItems({
    page: page.value,
    order: order.value,
  });
}

useMercureList({ store: roomreservationListStore, deleteStore: roomreservationDeleteStore });

sendRequest();

const headers = [
  {
    title: t("actions"),
    key: "actions",
    sortable: false,
  },
  { title: t("id"), key: "@id" },
  {
    title: t("roomreservation.created"),
    key: "created",
    sortable: false,
  },
  {
    title: t("roomreservation.roomId"),
    key: "roomId",
    sortable: false,
  },
  {
    title: t("roomreservation.client"),
    key: "client",
    sortable: false,
  },
  {
    title: t("roomreservation.status"),
    key: "status",
    sortable: false,
  },
  {
    title: t("roomreservation.paymentTransaction"),
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


function goToShowPage(item: RoomReservation) {
  router.push({
    name: "RoomReservationShow",
    params: { id: item["@id"] },
  });
}

function goToCreatePage() {
  router.push({
    name: "RoomReservationCreate",
  });
}

function goToUpdatePage(item: RoomReservation) {
  router.push({
    name: "RoomReservationUpdate",
    params: { id: item["@id"] },
  });
}

async function deleteItem(item: RoomReservation) {
  await roomreservationDeleteStore.deleteItem(item);

  sendRequest();
}

onBeforeUnmount(() => {
  roomreservationDeleteStore.$reset();
});
</script>
