// app.js
import _ from 'lodash';
import './bootstrap';
import router from './router'
import store from './store'
import { createApp } from 'vue'
import Antd from 'ant-design-vue';
import App from './App.vue'

import 'ant-design-vue/dist/antd.css';

window.BASE_URL = import.meta.env.VITE_BASE_URL;

router.beforeEach((to, from) => {
    /**
     * Though being used as the redirection process checker.
     * LocalStorage token will not be utilized in the API request process since I am using Laravel - Sanctum.
     * The API call authentication will be validated through laravel_session and XSRF-TOKEN cookies in the headers.
     * 
     */
    if (!_.isEqualWith(to.name, 'login') && _.isEmpty(localStorage.getItem('token'))) {
        router.push({ 'name': 'login' });
    }
})

createApp(App)
    .use(router)
    .use(store)
    .use(Antd)
    .mount("#app")