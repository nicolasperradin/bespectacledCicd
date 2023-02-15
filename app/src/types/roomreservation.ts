import type { Item } from "./item";

export interface RoomReservation extends Item {
  created?: string;
  roomId?: any;
  client?: any;
  status?: number;
  paymentTransaction?: any;
}
