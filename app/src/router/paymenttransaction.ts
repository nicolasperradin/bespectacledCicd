const names = {
  list: "PaymentTransactionList",
  create: "PaymentTransactionCreate",
  update: "PaymentTransactionUpdate",
  show: "PaymentTransactionShow",
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
    path: "payment_transactions",
    component: () => import("@/views/paymenttransaction/ViewList.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list],
    },
  },
  {
    name: names.create,
    path: "payment_transactions/create",
    component: () => import("@/views/paymenttransaction/ViewCreate.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list, breadcrumbs.create],
    },
  },
  {
    name: names.update,
    path: "payment_transactions/edit/:id",
    component: () => import("@/views/paymenttransaction/ViewUpdate.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list, breadcrumbs.update],
    },
  },
  {
    name: names.show,
    path: "payment_transactions/show/:id",
    component: () => import("@/views/paymenttransaction/ViewShow.vue"),
    meta: {
      breadcrumb: [breadcrumbs.list, breadcrumbs.show],
    },
  },
];
