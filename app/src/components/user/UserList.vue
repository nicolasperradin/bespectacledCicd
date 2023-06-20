<template>
  <Toolbar
    :actions="['add']"
    :breadcrumb="breadcrumb"
    :is-loading="isLoading"
    @add="goToCreatePage"
  />

  <v-container fluid>
    <v-alert v-if="deleted" type="success" class="mb-4" closable="true">
      {{ $t("itemDeleted", [deleted["@id"]]) }}
    </v-alert>
    <v-alert v-if="mercureDeleted" type="success" class="mb-4" closable="true">
      {{ $t("itemDeletedByAnotherUser", [mercureDeleted["@id"]]) }}
    </v-alert>

    <v-alert v-if="error" type="error" class="mb-4" closable="true">
      {{ error }}
    </v-alert>

    <DataFilter @filter="onSendFilter" @reset="resetFilter">
      <template #filter>
        <Filter :values="filters" />
      </template>
    </DataFilter>

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
          :to="{ name: 'UserShow', params: { id: item.raw['@id'] } }"
        >
          {{ item.raw["@id"] }}
        </router-link>
      </template>

      <template #item.events="{ item }">
        <template v-if="router.hasRoute('EventShow')">
          <router-link
            v-for="event in item.raw.events"
            :to="{ name: 'EventShow', params: { id: event['@id'] } }"
            :key="event['@id']"
          >
            {{ event["@id"] }}

            <br />
          </router-link>
        </template>

        <template v-else>
          <p v-for="event in item.raw.events" :key="event['@id']">
            {{ event["@id"] }}
          </p>
        </template>
      </template>
      <template #item.tickets="{ item }">
        <template v-if="router.hasRoute('TicketShow')">
          <router-link
            v-for="ticket in item.raw.tickets"
            :to="{ name: 'TicketShow', params: { id: ticket['@id'] } }"
            :key="ticket['@id']"
          >
            {{ ticket["@id"] }}

            <br />
          </router-link>
        </template>

        <template v-else>
          <p v-for="ticket in item.raw.tickets" :key="ticket['@id']">
            {{ ticket["@id"] }}
          </p>
        </template>
      </template>
      <template #item.bookings="{ item }">
        <template v-if="router.hasRoute('BookingShow')">
          <router-link
            v-for="booking in item.raw.bookings"
            :to="{ name: 'BookingShow', params: { id: booking['@id'] } }"
            :key="booking['@id']"
          >
            {{ booking["@id"] }}

            <br />
          </router-link>
        </template>

        <template v-else>
          <p v-for="booking in item.raw.bookings" :key="booking['@id']">
            {{ booking["@id"] }}
          </p>
        </template>
      </template>
      <template #item.transactions="{ item }">
        <template v-if="router.hasRoute('TransactionShow')">
          <router-link
            v-for="transaction in item.raw.transactions"
            :to="{ name: 'TransactionShow', params: { id: transaction } }"
            :key="transaction"
          >
            {{ transaction }}

            <br />
          </router-link>
        </template>

        <template v-else>
          <p v-for="transaction in item.raw.transactions" :key="transaction">
            {{ transaction }}
          </p>
        </template>
      </template>
      <template #item.createdAt="{ item }">
        {{ formatDateTime(item.raw.createdAt) }}
      </template>
            <template #item.updatedAt="{ item }">
        {{ formatDateTime(item.raw.updatedAt) }}
      </template>
            <template #item.deletedAt="{ item }">
        {{ formatDateTime(item.raw.deletedAt) }}
      </template>
          </v-data-table-server>
  </v-container>
</template>

<script setup lang="ts">
import { ref, onBeforeUnmount, Ref } from "vue";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import { storeToRefs } from "pinia";
import { useUserListStore } from "@/store/user/list";
import { useUserDeleteStore } from "@/store/user/delete";
import Toolbar from "@/components/common/Toolbar.vue";
import DataFilter from "@/components/common/DataFilter.vue";
import Filter from "@/components/user/UserFilter.vue";
import ActionCell from "@/components/common/ActionCell.vue";
import { formatDateTime } from "@/utils/date";
import { useMercureList } from "@/composables/mercureList";
import { useBreadcrumb } from "@/composables/breadcrumb";
import type { Filters, VuetifyOrder } from "@/types/list";
import type { User } from "@/types/user";

const { t } = useI18n();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const userDeleteStore = useUserDeleteStore();
const { deleted, mercureDeleted } = storeToRefs(userDeleteStore);

const userListStore = useUserListStore();
const { items, totalItems, error, isLoading } = storeToRefs(userListStore);

const page = ref("1");
const filters: Ref<Filters> = ref({});
const order = ref({});

async function sendRequest() {
  await userListStore.getItems({
    page: page.value,
    order: order.value,
    ...filters.value,
  });
}

useMercureList({ store: userListStore, deleteStore: userDeleteStore });

sendRequest();

const headers = [
  {
    title: t("actions"),
    key: "actions",
    sortable: false,
  },
  { title: t("id"), key: "@id" },
  {
    title: t("user.username"),
    key: "username",
    sortable: true,
  },
  {
    title: t("user.email"),
    key: "email",
    sortable: false,
  },
  {
    title: t("user.password"),
    key: "password",
    sortable: false,
  },
  {
    title: t("user.roles"),
    key: "roles",
    sortable: false,
  },
  {
    title: t("user.enabled"),
    key: "enabled",
    sortable: false,
  },
  {
    title: t("user.events"),
    key: "events",
    sortable: false,
  },
  {
    title: t("user.tickets"),
    key: "tickets",
    sortable: false,
  },
  {
    title: t("user.bookings"),
    key: "bookings",
    sortable: false,
  },
  {
    title: t("user.transactions"),
    key: "transactions",
    sortable: false,
  },
  {
    title: t("user.createdAt"),
    key: "createdAt",
    sortable: false,
  },
  {
    title: t("user.updatedAt"),
    key: "updatedAt",
    sortable: false,
  },
  {
    title: t("user.deletedAt"),
    key: "deletedAt",
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

function onSendFilter() {
  sendRequest();
}

function resetFilter() {
  filters.value = {};

  sendRequest();
}

function goToShowPage(item: User) {
  router.push({
    name: "UserShow",
    params: { id: item["@id"] },
  });
}

function goToCreatePage() {
  router.push({
    name: "UserCreate",
  });
}

function goToUpdatePage(item: User) {
  router.push({
    name: "UserUpdate",
    params: { id: item["@id"] },
  });
}

async function deleteItem(item: User) {
  await userDeleteStore.deleteItem(item);

  sendRequest();
}

onBeforeUnmount(() => {
  userDeleteStore.$reset();
});
</script>
