<template>
    <a-layout class="layout">
        <a-layout-header>
            <div class="logo" />
            <a-menu
                v-model:selectedKeys="selectedKeys"
                theme="dark"
                mode="horizontal"
                :style="{ lineHeight: '64px', width: '80%', float: 'left' }">
                <a-menu-item key="1">Products</a-menu-item>
            </a-menu>
            <a-button
                danger
                type="primary"
                @click="logoutUser"
                :style="{ marginTop: '15px', float: 'right' }">
                Logout
            </a-button>
        </a-layout-header>
        <a-layout-content style="padding: 0 50px">
            <a-breadcrumb style="margin: 16px 0">
                <a-breadcrumb-item>Products</a-breadcrumb-item>
            </a-breadcrumb>
            <div :style="{ background: '#fff', padding: '24px', minHeight: '280px' }">
                <slot name="content"></slot>
            </div>
        </a-layout-content>
        <a-layout-footer style="text-align: center">
            Assessment by Belleo Maraon
        </a-layout-footer>
    </a-layout>
</template>
<script>
import { defineComponent, ref, computed, onMounted } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';
import _ from 'lodash';

export default defineComponent({
    setup() {
        const store = useStore();
        const router = useRouter();
        const loggedInUserDetails = computed(() => store.getters.loggedInUserDetails );

        onMounted(() => {
            console.log('onMounted loggedInUserDetails', loggedInUserDetails);
            // check before other sub-components mounted
            // if (_.isUndefined(loggedInUserDetails) || !_.has(loggedInUserDetails, 'id')) {
                store.dispatch('getLoggedInUser').catch(() => {
                    // manage local storage
                    localStorage.removeItem('token');

                    return setTimeout(() => {
                        // redirect after getting user details
                        router.push({ 'name' : 'login' });
                    }, 1000);
                });
            // }
        });

        const logoutUser = () => {
            return store.dispatch('logoutUser').then(() => {
                // manage local storage
                localStorage.removeItem('token');

                return setTimeout(() => {
                    // redirect after getting user details
                    router.push({ 'name' : 'login' });
                }, 1000);
            });
        }

        return {
            logoutUser,
            tokenExists: !_.isEmpty(localStorage.getItem('token')),
            selectedKeys: ref(['1']),
        };
    },
});
</script>
<style>
.site-layout-content {
  min-height: 280px;
  padding: 24px;
  background: #fff;
}
#components-layout-demo-top .logo {
  float: left;
  width: 120px;
  height: 31px;
  margin: 16px 24px 16px 0;
  background: rgba(255, 255, 255, 0.3);
}
.ant-row-rtl #components-layout-demo-top .logo {
  float: right;
  margin: 16px 0 16px 24px;
}

[data-theme="dark"] .site-layout-content {
  background: #141414;
}
</style>