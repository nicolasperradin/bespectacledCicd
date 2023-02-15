import { defineStore } from "pinia";
import api from "@/utils/api";
import { extractHubURL } from "@/utils/mercure";
import type { RoomReservation } from "@/types/roomreservation";

interface State {
  retrieved?: RoomReservation;
  hubUrl?: URL;
  isLoading: boolean;
  error?: string;
}

export const useRoomReservationShowStore = defineStore("roomreservationShow", {
  state: (): State => ({
    retrieved: undefined,
    hubUrl: undefined,
    isLoading: false,
    error: undefined,
  }),

  actions: {
    async retrieve(id: string) {
      this.toggleLoading();

      try {
        const response = await api(id);
        const data: RoomReservation = await response.json();
        const hubUrl = extractHubURL(response);

        this.toggleLoading();
        this.setRetrieved(data);

        if (hubUrl) {
          this.setHubUrl(hubUrl);
        }
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

    setRetrieved(retrieved: RoomReservation) {
      this.retrieved = retrieved;
    },

    setHubUrl(hubUrl: URL) {
      this.hubUrl = hubUrl;
    },

    setError(error: string) {
      this.error = error;
    },
  },
});
