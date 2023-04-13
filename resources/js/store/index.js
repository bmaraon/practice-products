import { createStore } from 'vuex'
import authentication from "./modules/authentication";
import user from "./modules/user";
import productCategory from "./modules/productCategory";
import product from "./modules/product";

// Create a new store instance.
const store = createStore({
    modules: {
        authentication: authentication,
        user: user,
        productCategory: productCategory,
        product: product,
    }
});

export default store;
