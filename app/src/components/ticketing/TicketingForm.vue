<template>
  <v-form ref="form" @submit.prevent="emitSubmit">
    <v-row>
      <v-col cols="12" sm="6" md="6">
        <v-text-field
          v-model="item.buyer"
          :error="Boolean(violations?.buyer)"
          :error-messages="violations?.buyer"
          :label="$t('ticketing.buyer')"
          required
        >
          <template #append-inner>
            <v-icon
              style="cursor: pointer"
              @click.prevent.stop="item.buyer = undefined"
            >
              mdi-close
            </v-icon>
          </template>
        </v-text-field>
      </v-col>
      <v-col cols="12" sm="6" md="6">
        <v-text-field
          v-model="item.event"
          :error="Boolean(violations?.event)"
          :error-messages="violations?.event"
          :label="$t('ticketing.event')"
          required
        >
          <template #append-inner>
            <v-icon
              style="cursor: pointer"
              @click.prevent.stop="item.event = undefined"
            >
              mdi-close
            </v-icon>
          </template>
        </v-text-field>
      </v-col>
      <v-col cols="12" sm="6" md="6">
        <v-text-field
          v-model.number="item.status"
          :error="Boolean(violations?.status)"
          :error-messages="violations?.status"
          :label="$t('ticketing.status')"
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
          v-model="item.paymentTransaction"
          :error="Boolean(violations?.paymentTransaction)"
          :error-messages="violations?.paymentTransaction"
          :label="$t('ticketing.paymentTransaction')"
        >
          <template #append-inner>
            <v-icon
              style="cursor: pointer"
              @click.prevent.stop="item.paymentTransaction = undefined"
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
import type { Ticketing } from "@/types/ticketing";
import type { SubmissionErrors } from "@/types/error";
const props = defineProps<{
  values?: Ticketing;
  errors?: SubmissionErrors;
}>();

const violations = toRef(props, "errors");

const item: Ref<Ticketing> = ref({});

if (props.values) {
  item.value = {
    ...props.values,
  };
}

const emit = defineEmits<{
  (e: "submit", item: Ticketing): void;
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
