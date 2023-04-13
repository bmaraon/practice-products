<template>
    <a-row>
        <a-col :span="12" :offset="6">
            <a-row>
                <a-col :span="12" :offset="6">
                    <a-form
                        id="login-form"
                        :model="formState"
                        name="basic"
                        :label-col="{ span: 8 }"
                        :wrapper-col="{ span: 16 }"
                        autocomplete="off"
                        @finish="onFinish"
                        @finishFailed="onFinishFailed">
                        <h1 class="text-align-center">UserLution Login</h1>
                        <a-alert
                            class="text-align-center"
                            v-if="(typeof loginMessage != 'undefined') && loginMessage.error != ''"
                            :message="loginMessage.error" 
                            type="error" />
                        <a-alert
                            class="text-align-center"
                            v-if="(typeof loginMessage != 'undefined') && loginMessage.success != ''"
                            :message="loginMessage.success"
                            type="success" />
                        <br/>
                        <a-form-item
                            label="Email"
                            name="email"
                            :rules="[{ required: true, message: 'Please input your email!' }]">
                        <a-input v-model:value="formState.email" />
                        </a-form-item>

                        <a-form-item
                            label="Password"
                            name="password"
                            :rules="[{ required: true, message: 'Please input your password!' }]">
                            <a-input-password v-model:value="formState.password" />
                            </a-form-item>

                            <!-- <a-form-item name="remember" :wrapper-col="{ offset: 8, span: 16 }">
                            <a-checkbox v-model:checked="formState.remember">Remember me</a-checkbox>
                            </a-form-item> -->

                            <a-form-item :wrapper-col="{ offset: 8, span: 16 }">
                            <a-button type="primary" html-type="submit">Submit</a-button>
                        </a-form-item>
                    </a-form>
                </a-col>
            </a-row>
        </a-col>
    </a-row>
</template>

<style scoped>
#login-form {
  margin-top: 50%;
}
.text-align-center {
  text-align: center;
}
</style>

<script>
import { defineComponent, reactive, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router'
import { useStore } from 'vuex';
import _ from 'lodash';

export default defineComponent({
  setup() {
    const store = useStore();
    const router = useRouter();
    const loginMessage = computed(() => store.getters.getLoginMessage);

    // form inputs
    const formState = reactive({
      email: '',
      password: '',
      remember: true,
    });

    onMounted(() => {
        store.commit('setLoginMessage', {
            success: '',
            error: ''
        });
    })

    // form submit
    const onFinish = values => {
        store.dispatch('loginUser', values)
            .then(function (response) {
                store.commit('setLoginMessage', {
                    error: '',
                    success: response?.data?.message
                });

                // set localstorage token
                localStorage.setItem('token', response?.data?.data?.token);

                // redirect to dashboard page
                setTimeout(() => {
                    router.push({ 'name' : 'dashboard' });
                }, 2000);
            })
            .catch(function (error) {
                store.commit('setLoginMessage', {
                    success: '',
                    error: error?.response?.data?.message
                });
            });
    };

    // form validation eror
    const onFinishFailed = errorInfo => {
      console.log('Failed:', errorInfo);
    };

    return {
      formState,
      loginMessage,
      onFinish,
      onFinishFailed,
    };
  },
});
</script>