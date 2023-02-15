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
          :to="{ name: 'RoomShow', params: { id: item.raw['@id'] } }"
        >
          {{ item.raw["@id"] }}
        </router-link>
      </template>

      <template #item.events="{ item }">
        <template v-if="router.hasRoute('EventShow')">
          <router-link
            v-for="event in item.raw.events"
            :to="{ name: 'EventShow', params: { id: event } }"
            :key="event"
          >
            {{ event }}

            <br />
          </router-link>
        </template>

        <template v-else>
          <p v-for="event in item.raw.events" :key="event">
            {{ event }}
          </p>
        </template>
      </template>
    </v-data-table-server>
  </v-container>
</template>

<script setup lang="ts">
import { ref, onBeforeUnmount } from "vue";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import { useRoomListStore } from "@/store/room/list";
import { useRoomDeleteStore } from "@/store/room/delete";
import Toolbar from "@/components/common/Toolbar.vue";
import ActionCell from "@/components/common/ActionCell.vue";
import { useMercureList } from "@/composables/mercureList";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { VuetifyOrder } from "@/types/list";
import type { Room } from "@/types/room";

const { t } = useI18n();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const roomDeleteStore = useRoomDeleteStore();
const { deleted, mercureDeleted } = storeToRefs(roomDeleteStore);

const roomListStore = useRoomListStore();
const { items, totalItems, error, isLoading } = storeToRefs(roomListStore);

const page = ref("1");
const order = ref({});

async function sendRequest() {
  await roomListStore.getItems({
    page: page.value,
    order: order.value,
  });
}

useMercureList({ store: roomListStore, deleteStore: roomDeleteStore });

sendRequest();

const headers = [
  {
    title: t("actions"),
    key: "actions",
    sortable: false,
  },
  { title: t("id"), key: "@id" },
  {
    title: t("room.name"),
    key: "name",
    sortable: false,
  },
  {
    title: t("room.nbSeats"),
    key: "nbSeats",
    sortable: false,
  },
  {
    title: t("room.price"),
    key: "price",
    sortable: false,
  },
  {
    title: t("room.events"),
    key: "events",
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


function goToShowPage(item: Room) {
  router.push({
    name: "RoomShow",
    params: { id: item["@id"] },
  });
}

function goToCreatePage() {
  router.push({
    name: "RoomCreate",
  });
}

function goToUpdatePage(item: Room) {
  router.push({
    name: "RoomUpdate",
    params: { id: item["@id"] },
  });
}

async function deleteItem(item: Room) {
  await roomDeleteStore.deleteItem(item);

  sendRequest();
}

onBeforeUnmount(() => {
  roomDeleteStore.$reset();
});
</script>
