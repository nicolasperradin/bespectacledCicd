import { defineStore } from "pinia";
import api from "@/utils/api";
import type { Ticketing } from "@/types/ticketing";

interface State {
  deleted?: Ticketing;
  mercureDeleted?: Ticketing;
  isLoading: boolean;
  error?: string;
}

export const useTicketingDeleteStore = defineStore("ticketingDelete", {
  state: (): State => ({
    deleted: undefined,
    mercureDeleted: undefined,
    isLoading: false,
    error: undefined,
  }),

  actions: {
    async deleteItem(item: Ticketing) {
      this.setError("");
      this.toggleLoading();

      if (!item?.["@id"]) {
        this.setError("No ticketing found. Please reload");
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

    setDeleted(deleted: Ticketing) {
      this.deleted = deleted;
    },

    setMercureDeleted(mercureDeleted: Ticketing | undefined) {
      this.mercureDeleted = mercureDeleted;
    },

    setError(error: string) {
      this.error = error;
    },
  },
});
