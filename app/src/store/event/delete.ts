import { defineStore } from "pinia";
import api from "@/utils/api";
import type { Event } from "@/types/event";

interface State {
  deleted?: Event;
  mercureDeleted?: Event;
  isLoading: boolean;
  error?: string;
}

export const useEventDeleteStore = defineStore("eventDelete", {
  state: (): State => ({
    deleted: undefined,
    mercureDeleted: undefined,
    isLoading: false,
    error: undefined,
  }),

  actions: {
    async deleteItem(item: Event) {
      this.setError("");
      this.toggleLoading();

      if (!item?.["@id"]) {
        this.setError("No event found. Please reload");
        return;
      }

      try {
        await api(item["@id"], { method: "DELETE" });

        this.toggleLoading();
        this.setDeleted(item);
        this.setMercureDeleted(undefined);
      } catch (error) {
        this.toggleLoading();

        if (error instanceof Error) {
          this.setError(error.message);
        }
      }
    },

    toggleLoading() {
      this.isLoading = !this.isLoading;
    },

    setDeleted(deleted: Event) {
      this.deleted = deleted;
    },

    setMercureDeleted(mercureDeleted: Event | undefined) {
      this.mercureDeleted = mercureDeleted;
    },

    setError(error: string) {
      this.error = error;
    },
  },
});
