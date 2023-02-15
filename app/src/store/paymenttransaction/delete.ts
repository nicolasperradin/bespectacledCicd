import { defineStore } from "pinia";
import api from "@/utils/api";
import type { PaymentTransaction } from "@/types/paymenttransaction";

interface State {
  deleted?: PaymentTransaction;
  mercureDeleted?: PaymentTransaction;
  isLoading: boolean;
  error?: string;
}

export const usePaymentTransactionDeleteStore = defineStore(
  "paymenttransactionDelete",
  {
    state: (): State => ({
      deleted: undefined,
      mercureDeleted: undefined,
      isLoading: false,
      error: undefined,
    }),

    actions: {
      async deleteItem(item: PaymentTransaction) {
        this.setError("");
        this.toggleLoading();

        if (!item?.["@id"]) {
          this.setError("No paymenttransaction found. Please reload");
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

      setDeleted(deleted: PaymentTransaction) {
        this.deleted = deleted;
      },

      setMercureDeleted(mercureDeleted: PaymentTransaction | undefined) {
        this.mercureDeleted = mercureDeleted;
      },

      setError(error: string) {
        this.error = error;
      },
    },
  }
);
