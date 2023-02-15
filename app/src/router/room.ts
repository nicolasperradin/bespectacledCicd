const names = {
  list: "RoomList",
  create: "RoomCreate",
  update: "RoomUpdate",
  show: "RoomShow",
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
    path: "rooms",
    component: () => import("@/views/room/ViewList.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list],
    },
  },
  {
    name: names.create,
    path: "rooms/create",
    component: () => import("@/views/room/ViewCreate.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list, breadcrumbs.create],
    },
  },
  {
    name: names.update,
    path: "rooms/edit/:id",
    component: () => import("@/views/room/ViewUpdate.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list, breadcrumbs.update],
    },
  },
  {
    name: names.show,
    path: "rooms/show/:id",
    component: () => import("@/views/room/ViewShow.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list, breadcrumbs.show],
    },
  },
];
