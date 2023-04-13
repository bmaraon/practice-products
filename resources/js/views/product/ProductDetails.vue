<template>
    <MainLayout>
        <template #content>
            <a-row>
                <a-col :span="12" :offset="6">
                    <a-form
                        :model="formState"
                        v-bind="layout"
                        name="nest-messages"
                        :validate-messages="validateMessages"
                        @finish="onFinish">
                        <a-form-item readonly :name="name" label="Name" :rules="[{ required: true }]">
                            <a-input v-model:value="formState.product.name" />
                        </a-form-item>
                        <!-- <a-form-item :name="['product', 'description']" label="Description" :rules="[{ type: 'string' }]">
                            <a-input v-model:value="formState.product.description" />
                        </a-form-item>
                        <a-form-item :name="['product', 'quantity']" label="Quantity" :rules="[{ type: 'number', min: 0, max: 99 }]">
                            <a-input-number v-model:value="formState.product.quantity" />
                        </a-form-item>
                        <a-form-item :name="['product', 'price']" label="Price">
                            <a-input v-model:value="formState.product.price" />
                        </a-form-item>
                        <a-form-item :name="['product', 'product_category']" label="product_category">
                            <a-textarea v-model:value="formState.product.product_category.name" />
                        </a-form-item> -->
                        <!-- <a-form-item :wrapper-col="{ ...layout.wrapperCol, offset: 8 }">
                            <a-button type="primary" html-type="submit">Submit</a-button>
                        </a-form-item> -->
                        <a-form-item :wrapper-col="{ ...layout.wrapperCol, offset: 8 }">
                            <a-button type="primary" html-type="button">Back to List</a-button>
                        </a-form-item>
                    </a-form>
                </a-col>
            </a-row>
        </template>
    </MainLayout>
</template>
<script>
import { defineComponent, reactive, onMounted, computed } from 'vue';
import { useRoute  } from 'vue-router';
import { useStore } from 'vuex';
import _ from 'lodash';

import MainLayout from '@/components/MainLayout.vue'

export default defineComponent({
    components: {
        MainLayout
    },
    setup() {
        const store = useStore();
        const route = useRoute();
        const loggedInUserDetails = computed(() => store.getters.loggedInUserDetails );
        const productDetails = computed(() => store.getters.productDetails );

        const layout = {
            labelCol: {
            span: 8,
            },
            wrapperCol: {
            span: 16,
            },
        };

        const validateMessages = {
            required: '${label} is required!',
            types: {
                email: '${label} is not a valid email!',
                number: '${label} is not a valid number!',
            },
            number: {
                range: '${label} must be between ${min} and ${max}',
            },
        };

        const formState = reactive({
            product: productDetails,
        });

        onMounted(() => {
            // get user details
            if (_.isUndefined(loggedInUserDetails) || !_.has(loggedInUserDetails, 'id')) {
                store.dispatch('getLoggedInUser')
                    .then(() => {
                        store.dispatch('getProductDetails', { id: route.params.id });
                    });
            }
        })

        const onFinish = values => {
            console.log('Success:', values);
        };

        return {
            formState,
            onFinish,
            layout,
            validateMessages,
        };
    },
});
</script>