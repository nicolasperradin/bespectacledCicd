import { createWebHistory, createRouter } from "vue-router";
import LoginPage from "./components/Login.vue";
import RegisterPage from "./components/Register.vue";
import ForgetPasswordPage from "./components/ForgetPassword.vue";
// lazy-loaded
const DashboardPage = () => import("./components/Dashboard.vue")

const routes = [
  {
    path: "/",
    component: LoginPage,
  },
  {
    path: "/login",
    component: LoginPage,
  },
  {
    path: "/register",
    component: RegisterPage,
  },
  {
    path: "/forget-password",
    component: ForgetPasswordPage,
  },
  {
    path: "/dashboard",
    name: "dashboard",
    // lazy-loaded
    component: DashboardPage,
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;

router.beforeEach((to, from, next) => {
  const publicPages = ['/login', '/register', '/home', '/forget-password'];
  const authRequired = !publicPages.includes(to.path);
  const loggedIn = localStorage.getItem('user');
  // trying to access a restricted page + not logged in
  // redirect to login page
  if (authRequired && !loggedIn) {
    next('/login');
  } else {
    next();
  }
});