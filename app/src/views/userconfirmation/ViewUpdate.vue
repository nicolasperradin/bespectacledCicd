<template>
  <Suspense>
    <Update />

    <template #fallback>{{ $t("loading") }}</template>
  </Suspense>
</template>

<script setup lang="ts">
import Update from "@/components/userconfirmation/UserConfirmationUpdate.vue";
</script>
