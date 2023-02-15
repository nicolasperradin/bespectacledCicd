const names = {
  list: "UserConfirmationList",
  create: "UserConfirmationCreate",
  update: "UserConfirmationUpdate",
  show: "UserConfirmationShow",
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
    path: "user_confirmations",
    component: () => import("@/views/userconfirmation/ViewList.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list],
    },
  },
  {
    name: names.create,
    path: "user_confirmations/create",
    component: () => import("@/views/userconfirmation/ViewCreate.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list, breadcrumbs.create],
    },
  },
  {
    name: names.update,
    path: "user_confirmations/edit/:id",
    component: () => import("@/views/userconfirmation/ViewUpdate.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list, breadcrumbs.update],
    },
  },
  {
    name: names.show,
    path: "user_confirmations/show/:id",
    component: () => import("@/views/userconfirmation/ViewShow.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list, breadcrumbs.show],
    },
  },
];
