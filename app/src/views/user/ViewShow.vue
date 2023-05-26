<script setup lang="ts">
//import Show from "@/components/user/UserShow.vue";
</script>

<template>
  <Suspense>
    <Show />

    <template #fallback>{{ $t("loading") }}</template>
  </Suspense>
</template>