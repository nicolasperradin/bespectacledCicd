<template>
  <Suspense>
    <List />

    <template #fallback>{{ $t("loading") }}</template>
  </Suspense>
</template>

<script setup lang="ts">
import List from "@/components/ticketing/TicketingList.vue";
</script>
