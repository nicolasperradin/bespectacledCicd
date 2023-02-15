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
          :to="{ name: 'EventShow', params: { id: item.raw['@id'] } }"
        >
          {{ item.raw["@id"] }}
        </router-link>
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
      <template #item.users="{ item }">
        <template v-if="router.hasRoute('UserShow')">
          <router-link
            v-for="user in item.raw.users"
            :to="{ name: 'UserShow', params: { id: user } }"
            :key="user"
          >
            {{ user }}

            <br />
          </router-link>
        </template>

        <template v-else>
          <p v-for="user in item.raw.users" :key="user">
            {{ user }}
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
import { useEventListStore } from "@/store/event/list";
import { useEventDeleteStore } from "@/store/event/delete";
import Toolbar from "@/components/common/Toolbar.vue";
import ActionCell from "@/components/common/ActionCell.vue";
import { useMercureList } from "@/composables/mercureList";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { VuetifyOrder } from "@/types/list";
import type { Event } from "@/types/event";

const { t } = useI18n();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const eventDeleteStore = useEventDeleteStore();
const { deleted, mercureDeleted } = storeToRefs(eventDeleteStore);

const eventListStore = useEventListStore();
const { items, totalItems, error, isLoading } = storeToRefs(eventListStore);

const page = ref("1");
const order = ref({});

async function sendRequest() {
  await eventListStore.getItems({
    page: page.value,
    order: order.value,
  });
}

useMercureList({ store: eventListStore, deleteStore: eventDeleteStore });

sendRequest();

const headers = [
  {
    title: t("actions"),
    key: "actions",
    sortable: false,
  },
  { title: t("id"), key: "@id" },
  {
    title: t("event.name"),
    key: "name",
    sortable: false,
  },
  {
    title: t("event.room"),
    key: "room",
    sortable: false,
  },
  {
    title: t("event.artists"),
    key: "artists",
    sortable: false,
  },
  {
    title: t("event.price"),
    key: "price",
    sortable: false,
  },
  {
    title: t("event.schedules"),
    key: "schedules",
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


function goToShowPage(item: Event) {
  router.push({
    name: "EventShow",
    params: { id: item["@id"] },
  });
}

function goToCreatePage() {
  router.push({
    name: "EventCreate",
  });
}

function goToUpdatePage(item: Event) {
  router.push({
    name: "EventUpdate",
    params: { id: item["@id"] },
  });
}

async function deleteItem(item: Event) {
  await eventDeleteStore.deleteItem(item);

  sendRequest();
}

onBeforeUnmount(() => {
  eventDeleteStore.$reset();
});
</script>
