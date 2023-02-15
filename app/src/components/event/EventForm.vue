<template>
  <v-form ref="form" @submit.prevent="emitSubmit">
    <v-row>
      <v-col cols="12" sm="6" md="6">
        <v-text-field
          v-model="item.name"
          :error="Boolean(violations?.name)"
          :error-messages="violations?.name"
          :label="$t('event.name')"
          required
        >
          <template #append-inner>
            <v-icon
              style="cursor: pointer"
              @click.prevent.stop="item.name = undefined"
            >
              mdi-close
            </v-icon>
          </template>
        </v-text-field>
      </v-col>
      <v-col cols="12" sm="6" md="6">
        <v-text-field
          v-model="item.room"
          :error="Boolean(violations?.room)"
          :error-messages="violations?.room"
          :label="$t('event.room')"
          required
        >
          <template #append-inner>
            <v-icon
              style="cursor: pointer"
              @click.prevent.stop="item.room = undefined"
            >
              mdi-close
            </v-icon>
          </template>
        </v-text-field>
      </v-col>
      <v-col cols="12">
        <FormRepeater
          :values="item.artists"
          :label="$t('event.artists')"
          @update="(values: any) => (item.artists = values)"
        />
      </v-col>
      <v-col cols="12" sm="6" md="6">
        <v-text-field
          v-model.number="item.price"
          :error="Boolean(violations?.price)"
          :error-messages="violations?.price"
          :label="$t('event.price')"
          required
        >
          <template #append-inner>
            <v-icon
              style="cursor: pointer"
              @click.prevent.stop="item.price = undefined"
            >
              mdi-close
            </v-icon>
          </template>
        </v-text-field>
      </v-col>
      <v-col cols="12" sm="6" md="6">
        <v-text-field
          v-model="item.schedules"
          :error="Boolean(violations?.schedules)"
          :error-messages="violations?.schedules"
          :label="$t('event.schedules')"
        >
          <template #append-inner>
            <v-icon
              style="cursor: pointer"
              @click.prevent.stop="item.schedules = undefined"
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
import FormRepeater from "@/components/common/FormRepeater.vue";
import type { Item } from "@/types/item";
import type { Event } from "@/types/event";
import type { SubmissionErrors } from "@/types/error";
const props = defineProps<{
  values?: Event;
  errors?: SubmissionErrors;
}>();

const violations = toRef(props, "errors");

const item: Ref<Event> = ref({});

if (props.values) {
  item.value = {
    ...props.values,
  };
}

const emit = defineEmits<{
  (e: "submit", item: Event): void;
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
