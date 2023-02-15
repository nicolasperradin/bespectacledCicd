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
            {{ $t("ticketing.buyer") }}
          </td>

          <td>
            <router-link
              v-if="router.hasRoute('UserShow')"
              :to="{ name: 'UserShow', params: { id: item.user } }"
            >
              {{ item.user }}
            </router-link>

            <p v-else>
              {{ item.user }}
            </p>
          </td>
        </tr>
        <tr>
          <td>
            {{ $t("ticketing.event") }}
          </td>

          <td>
            <router-link
              v-if="router.hasRoute('EventShow')"
              :to="{ name: 'EventShow', params: { id: item.event } }"
            >
              {{ item.event }}
            </router-link>

            <p v-else>
              {{ item.event }}
            </p>
          </td>
        </tr>
        <tr>
          <td>
            {{ $t("ticketing.status") }}
          </td>

          <td>
            {{ item.status }}
                      </td>
        </tr>
        <tr>
          <td>
            {{ $t("ticketing.paymentTransaction") }}
          </td>

          <td>
            <router-link
              v-if="router.hasRoute('PaymentTransactionShow')"
              :to="{ name: 'PaymentTransactionShow', params: { id: item.paymenttransaction } }"
            >
              {{ item.paymenttransaction }}
            </router-link>

            <p v-else>
              {{ item.paymenttransaction }}
            </p>
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
import { useTicketingDeleteStore } from "@/store/ticketing/delete";
import { useTicketingShowStore } from "@/store/ticketing/show";
import { useBreadcrumb } from "@/composables/breadcrumb";

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const breadcrumb = useBreadcrumb();

const ticketingShowStore = useTicketingShowStore();
const { retrieved: item, isLoading, error } = storeToRefs(ticketingShowStore);

const ticketingDeleteStore = useTicketingDeleteStore();
const { deleted, error: deleteError } = storeToRefs(ticketingDeleteStore);

useMercureItem({
  store: ticketingShowStore,
  deleteStore: ticketingDeleteStore,
  redirectRouteName: "TicketingList",
});

await ticketingShowStore.retrieve(decodeURIComponent(route.params.id as string));

async function deleteItem() {
  if (!item?.value) {
    ticketingDeleteStore.setError(t("itemNotFound"));
    return;
  }

  await ticketingDeleteStore.deleteItem(item.value);

  if (!deleted?.value) {
    return;
  }

  router.push({ name: "TicketingList" });
}

onBeforeUnmount(() => {
  ticketingShowStore.$reset();
});
</script>
