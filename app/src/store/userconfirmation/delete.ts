import { defineStore } from "pinia";
import api from "@/utils/api";
import type { UserConfirmation } from "@/types/userconfirmation";

interface State {
  deleted?: UserConfirmation;
  mercureDeleted?: UserConfirmation;
  isLoading: boolean;
  error?: string;
}

export const useUserConfirmationDeleteStore = defineStore(
  "userconfirmationDelete",
  {
    state: (): State => ({
      deleted: undefined,
      mercureDeleted: undefined,
      isLoading: false,
      error: undefined,
    }),

    actions: {
      async deleteItem(item: UserConfirmation) {
        this.setError("");
        this.toggleLoading();

        if (!item?.["@id"]) {
          this.setError("No userconfirmation found. Please reload");
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

      setDeleted(deleted: UserConfirmation) {
        this.deleted = deleted;
      },

      setMercureDeleted(mercureDeleted: UserConfirmation | undefined) {
        this.mercureDeleted = mercureDeleted;
      },

      setError(error: string) {
        this.error = error;
      },
    },
  }
);
