import { createRouter, createWebHistory } from "vue-router";
import dashboard from "@/view/dashboard.vue";
import login from "@/view/login.vue";
import register from "@/view/register.vue";
import sovenir from "@/view/sovernir.vue";
import tambah from "@/view/tambah.vue";
import edit from "@/view/edit.vue";
import view from "@/view/view.vue";

const routes = [
  { path: "", name: "Dashboard", component: dashboard },
  { path: "/login", name: "Login", component: login },
  { path: "/register", name: "register", component: register },
  { path: "/souvenir", name: "Souvenir", component: sovenir },
  { path: "/tambah", name: "tambah", component: tambah },
  { path: "/edit:id", name: "edit", component: edit },
  { path: "/view:id", name: "view", component: view },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
