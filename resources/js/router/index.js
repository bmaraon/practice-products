import { createWebHistory, createRouter } from "vue-router";
import Dashboard from "@/views/Dashboard.vue";
import Login from "@/views/Login.vue";
import ProductList from "@/views/product/ProductList.vue";
import ProductDetails from "@/views/product/ProductDetails.vue";

const routes = [
    {
        path: "/",
        name: "dashboard",
        component: Dashboard,
    },
    {
        path: "/login",
        name: "login",
        component: Login,
    },
    {
        path: "/products",
        name: "product-list",
        component: ProductList,
    },
    {
        path: "/products/:id",
        name: "product-details",
        component: ProductDetails,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;