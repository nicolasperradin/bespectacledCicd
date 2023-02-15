import type { Item } from "./item";

export interface UserConfirmation extends Item {
  confirmationToken?: string;
}
