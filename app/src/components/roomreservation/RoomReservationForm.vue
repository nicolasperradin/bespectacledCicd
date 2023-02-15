<template>
  <v-form ref="form" @submit.prevent="emitSubmit">
    <v-row>
      <v-col cols="12" sm="6" md="6">
        <v-text-field
          v-model="item.created"
          :error="Boolean(violations?.created)"
          :error-messages="violations?.created"
          :label="$t('roomreservation.created')"
          type="date"
        >
          <template #append-inner>
            <v-icon
              style="cursor: pointer"
              @click.prevent.stop="item.created = undefined"
            >
              mdi-close
            </v-icon>
          </template>
        </v-text-field>
      </v-col>
      <v-col cols="12" sm="6" md="6">
        <v-text-field
          v-model="item.roomId"
          :error="Boolean(violations?.roomId)"
          :error-messages="violations?.roomId"
          :label="$t('roomreservation.roomId')"
          required
        >
          <template #append-inner>
            <v-icon
              style="cursor: pointer"
              @click.prevent.stop="item.roomId = undefined"
            >
              mdi-close
            </v-icon>
          </template>
        </v-text-field>
      </v-col>
      <v-col cols="12" sm="6" md="6">
        <v-text-field
          v-model="item.client"
          :error="Boolean(violations?.client)"
          :error-messages="violations?.client"
          :label="$t('roomreservation.client')"
          required
        >
          <template #append-inner>
            <v-icon
              style="cursor: pointer"
              @click.prevent.stop="item.client = undefined"
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
          :label="$t('roomreservation.status')"
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
          :label="$t('roomreservation.paymentTransaction')"
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
import { formatDateInput } from "@/utils/date";
import type { RoomReservation } from "@/types/roomreservation";
import type { SubmissionErrors } from "@/types/error";
const props = defineProps<{
  values?: RoomReservation;
  errors?: SubmissionErrors;
}>();

const violations = toRef(props, "errors");

const item: Ref<RoomReservation> = ref({});

if (props.values) {
  item.value = {
    ...props.values,
    publicationDate: formatDateInput(props.values.publicationDate),
  };
}

const emit = defineEmits<{
  (e: "submit", item: RoomReservation): void;
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
