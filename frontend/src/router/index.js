import { createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import UserProfile from "../views/UserProfile.vue";
import Detail from "../views/Detail.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: Home,
      meta: {
        auth: true,
      },
    },
    {
      path: "/login",
      name: "login",
      component: Login,
      meta: {
        auth: false,
        cat: "auth",
      },
    },
    {
      path: "/register",
      name: "register",
      component: Register,
      meta: {
        auth: false,
        cat: "auth",
      },
    },
    {
      path: "/profile",
      name: "profile",
      component: UserProfile,
      meta: {
        auth: true,
      },
    },
    {
      path: "/:tweetId",
      name: "detail",
      component: Detail,
      meta: {
        auth: true,
      },
    },
  ],
});

router.beforeEach((to, from, next) => {
  if (to.meta.auth && !localStorage.token) {
    next({ name: "login" });
  } else if (to.meta.cat == "auth" && localStorage.token) {
    next({ name: "home" });
  } else {
    next();
  }
});

export default router;
