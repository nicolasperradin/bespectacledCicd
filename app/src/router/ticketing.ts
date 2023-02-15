const names = {
  list: "TicketingList",
  create: "TicketingCreate",
  update: "TicketingUpdate",
  show: "TicketingShow",
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
    path: "ticketings",
    component: () => import("@/views/ticketing/ViewList.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list],
    },
  },
  {
    name: names.create,
    path: "ticketings/create",
    component: () => import("@/views/ticketing/ViewCreate.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list, breadcrumbs.create],
    },
  },
  {
    name: names.update,
    path: "ticketings/edit/:id",
    component: () => import("@/views/ticketing/ViewUpdate.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list, breadcrumbs.update],
    },
  },
  {
    name: names.show,
    path: "ticketings/show/:id",
    component: () => import("@/views/ticketing/ViewShow.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list, breadcrumbs.show],
    },
  },
];
