import {createRouter, createWebHistory } from "vue-router";
import Home from "../components/login.vue";
import Users from "../components/users/index.vue"

const routes = [
    {
        path: "/",
        name: "home",
        component: Home,
    },
    {
        path: "/users",
        name: "users",
        component: Users,
    },
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
});

export default router;