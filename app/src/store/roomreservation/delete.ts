import { defineStore } from "pinia";
import api from "@/utils/api";
import type { RoomReservation } from "@/types/roomreservation";

interface State {
  deleted?: RoomReservation;
  mercureDeleted?: RoomReservation;
  isLoading: boolean;
  error?: string;
}

export const useRoomReservationDeleteStore = defineStore(
  "roomreservationDelete",
  {
    state: (): State => ({
      deleted: undefined,
      mercureDeleted: undefined,
      isLoading: false,
      error: undefined,
    }),

    actions: {
      async deleteItem(item: RoomReservation) {
        this.setError("");
        this.toggleLoading();

        if (!item?.["@id"]) {
          this.setError("No roomreservation found. Please reload");
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

      setDeleted(deleted: RoomReservation) {
        this.deleted = deleted;
      },

      setMercureDeleted(mercureDeleted: RoomReservation | undefined) {
        this.mercureDeleted = mercureDeleted;
      },

      setError(error: string) {
        this.error = error;
      },
    },
  }
);
