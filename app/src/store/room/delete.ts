import { defineStore } from "pinia";
import api from "@/utils/api";
import type { Room } from "@/types/room";

interface State {
  deleted?: Room;
  mercureDeleted?: Room;
  isLoading: boolean;
  error?: string;
}

export const useRoomDeleteStore = defineStore("roomDelete", {
  state: (): State => ({
    deleted: undefined,
    mercureDeleted: undefined,
    isLoading: false,
    error: undefined,
  }),

  actions: {
    async deleteItem(item: Room) {
      this.setError("");
      this.toggleLoading();

      if (!item?.["@id"]) {
        this.setError("No room found. Please reload");
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

    setDeleted(deleted: Room) {
      this.deleted = deleted;
    },

    setMercureDeleted(mercureDeleted: Room | undefined) {
      this.mercureDeleted = mercureDeleted;
    },

    setError(error: string) {
      this.error = error;
    },
  },
});
