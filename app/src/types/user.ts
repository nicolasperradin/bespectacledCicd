import type { Item } from "./item";

export interface User extends Item {
  email?: string;
  roles?: string[];
  password?: any;
  plainPassword?: any;
  username?: string;
  enabled?: boolean;
  confirmationToken?: string;
  createdAt?: any;
  updatedAt?: any;
  passwordChangeDate?: any;
  oldPassword?: string;
  newPassword?: string;
  newRetypedPassword?: string;
  roomReservations?: any;
  ticketings?: any;
  paymentTransactions?: any;
  validatedAccountArtist?: boolean;
  deletedAt?: any;
  events?: any;
}
