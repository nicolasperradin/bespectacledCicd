<script setup lang="ts">
import { useI18n } from 'vue-i18n'

import type { BreadcrumbValue } from '@/types/breadcrumb'

const { t } = useI18n()
const props = defineProps<{ breadcrumb: BreadcrumbValue[] }>()
const items = [{ title: t('admin'), to: '/admin' }, ...props.breadcrumb]
</script>

<template>
	<v-breadcrumbs :items="items" />
</template>

<style>
.v-breadcrumbs-item--active {
	font-size: 20px;
	font-weight: bold;
	opacity: 1 !important;
}
</style>