import { defineStore } from "pinia";
import api from "@/utils/api";
import type { Venue } from "@/types/venue";

interface State {
  deleted?: Venue;
  mercureDeleted?: Venue;
  isLoading: boolean;
  error?: string;
}

export const useVenueDeleteStore = defineStore("venueDelete", {
  state: (): State => ({
    deleted: undefined,
    mercureDeleted: undefined,
    isLoading: false,
    error: undefined,
  }),

  actions: {
    async deleteItem(item: Venue) {
      this.setError("");
      this.toggleLoading();

      if (!item?.["@id"]) {
        this.setError("No venue found. Please reload");
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

    setDeleted(deleted: Venue) {
      this.deleted = deleted;
    },

    setMercureDeleted(mercureDeleted: Venue | undefined) {
      this.mercureDeleted = mercureDeleted;
    },

    setError(error: string) {
      this.error = error;
    },
  },
});
