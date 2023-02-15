import type { Item } from "./item";

export interface Event extends Item {
  name?: string;
  room?: any;
  artists?: any;
  price?: decimal;
  schedules?: string;
}
