const names = {
  list: "RoomReservationList",
  create: "RoomReservationCreate",
  update: "RoomReservationUpdate",
  show: "RoomReservationShow",
};

const breadcrumbs = {
  list: { title: names.list, to: { name: names.list } },
  create: { title: names.create, to: { name: names.create } },
  update: { title: names.update, to: { name: names.update } },
  show: { title: names.show, to: { name: names.show } },
};

export default [
  {
    name: names.list,
    path: "room_reservations",
    component: () => import("@/views/roomreservation/ViewList.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list],
    },
  },
  {
    name: names.create,
    path: "room_reservations/create",
    component: () => import("@/views/roomreservation/ViewCreate.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list, breadcrumbs.create],
    },
  },
  {
    name: names.update,
    path: "room_reservations/edit/:id",
    component: () => import("@/views/roomreservation/ViewUpdate.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list, breadcrumbs.update],
    },
  },
  {
    name: names.show,
    path: "room_reservations/show/:id",
    component: () => import("@/views/roomreservation/ViewShow.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list, breadcrumbs.show],
    },
  },
];
