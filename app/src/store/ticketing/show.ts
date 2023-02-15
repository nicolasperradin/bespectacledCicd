import { defineStore } from "pinia";
import api from "@/utils/api";
import { extractHubURL } from "@/utils/mercure";
import type { Ticketing } from "@/types/ticketing";

interface State {
  retrieved?: Ticketing;
  hubUrl?: URL;
  isLoading: boolean;
  error?: string;
}

export const useTicketingShowStore = defineStore("ticketingShow", {
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
        const data: Ticketing = await response.json();
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

    setRetrieved(retrieved: Ticketing) {
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
