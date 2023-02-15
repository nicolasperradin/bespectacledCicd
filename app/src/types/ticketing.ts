import type { Item } from "./item";

export interface Ticketing extends Item {
  buyer?: any;
  event?: any;
  status?: number;
  paymentTransaction?: any;
}
