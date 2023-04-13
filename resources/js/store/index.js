import { createStore } from 'vuex'
import login from "./modules/login";
import user from "./modules/user";
import productCategory from "./modules/productCategory";
import product from "./modules/product";

// Create a new store instance.
const store = createStore({
    modules: {
        login: login,
        user: user,
        productCategory: productCategory,
        product: product,
    }
});

export default store;
