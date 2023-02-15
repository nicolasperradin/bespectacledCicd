import type { Item } from "./item";

export interface PaymentTransaction extends Item {
  amount?: decimal;
  status?: string;
  createdAt?: string;
}
