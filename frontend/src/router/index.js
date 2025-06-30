import Vue from "vue";
import Router from "vue-router";

import LoginPage from "@/components/LoginPage.vue";
import RegisterPage from "@/components/RegisterPage.vue";
import TodoListPage from "@/components/TodoListPage.vue";
import Profilepage from "@/components/Profilepage.vue";

Vue.use(Router);

const router = new Router({
  mode: "history",
  routes: [
    { path: "/", redirect: "/login" },
    { path: "/login", component: LoginPage },
    { path: "/register", component: RegisterPage },
    { path: "/todos", component: TodoListPage },
    { path: "/profile", component: Profilepage },
  ],
});

export default router;
