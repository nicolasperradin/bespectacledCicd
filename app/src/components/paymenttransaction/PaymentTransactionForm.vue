<template>
  <v-form ref="form" @submit.prevent="emitSubmit">
    <v-row>
      <v-col cols="12" sm="6" md="6">
        <v-text-field
          v-model.number="item.amount"
          :error="Boolean(violations?.amount)"
          :error-messages="violations?.amount"
          :label="$t('paymenttransaction.amount')"
        >
          <template #append-inner>
            <v-icon
              style="cursor: pointer"
              @click.prevent.stop="item.amount = undefined"
            >
              mdi-close
            </v-icon>
          </template>
        </v-text-field>
      </v-col>
      <v-col cols="12" sm="6" md="6">
        <v-text-field
          v-model="item.status"
          :error="Boolean(violations?.status)"
          :error-messages="violations?.status"
          :label="$t('paymenttransaction.status')"
          required
        >
          <template #append-inner>
            <v-icon
              style="cursor: pointer"
              @click.prevent.stop="item.status = undefined"
            >
              mdi-close
            </v-icon>
          </template>
        </v-text-field>
      </v-col>
      <v-col cols="12" sm="6" md="6">
        <v-text-field
          v-model="item.createdAt"
          :error="Boolean(violations?.createdAt)"
          :error-messages="violations?.createdAt"
          :label="$t('paymenttransaction.createdAt')"
          type="date"
        >
          <template #append-inner>
            <v-icon
              style="cursor: pointer"
              @click.prevent.stop="item.createdAt = undefined"
            >
              mdi-close
            </v-icon>
          </template>
        </v-text-field>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" sm="6" md="6">
        <v-btn color="primary" type="submit">{{ $t("submit") }}</v-btn>

        <v-btn color="primary" variant="text" class="ml-2" @click="resetForm">
          {{ $t("reset") }}
        </v-btn>
      </v-col>
    </v-row>
  </v-form>
</template>

<script setup lang="ts">
import { ref, Ref, toRef } from "vue";
import { VForm } from "vuetify/components";
import { formatDateInput } from "@/utils/date";
import type { PaymentTransaction } from "@/types/paymenttransaction";
import type { SubmissionErrors } from "@/types/error";
const props = defineProps<{
  values?: PaymentTransaction;
  errors?: SubmissionErrors;
}>();

const violations = toRef(props, "errors");

const item: Ref<PaymentTransaction> = ref({});

if (props.values) {
  item.value = {
    ...props.values,
    publicationDate: formatDateInput(props.values.publicationDate),
  };
}

const emit = defineEmits<{
  (e: "submit", item: PaymentTransaction): void;
}>();

function emitSubmit() {
  emit("submit", item.value);
}

const form: Ref<VForm | null> = ref(null);

function resetForm() {
  if (!form.value) return;

  form.value.reset();
}
</script>
