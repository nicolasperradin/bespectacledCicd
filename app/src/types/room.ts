import type { Item } from "./item";

export interface Room extends Item {
  name?: string;
  nbSeats?: number;
  price?: decimal;
  events?: any;
}
