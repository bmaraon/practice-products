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
                        <a-form-item readonly :name="name" label="Name">
                            <a-input v-model:value="formState.product.name" />
                        </a-form-item>
                        <a-form-item :name="['product', 'description']" label="Description">
                            <a-input v-model:value="formState.product.description" />
                        </a-form-item>
                        <a-form-item :name="['product', 'quantity']" label="Quantity">
                            <a-input-number v-model:value="formState.product.quantity" />
                        </a-form-item>
                        <a-form-item :name="['product', 'price']" label="Price">
                            <a-input v-model:value="formState.product.price" />
                        </a-form-item>
                        <a-form-item :name="['product', 'product_category_name']" label="Product Category">
                            <a-textarea v-model:value="formState.product.product_category_name" />
                        </a-form-item>
                        <!-- <a-form-item :wrapper-col="{ ...layout.wrapperCol, offset: 8 }">
                            <a-button type="primary" html-type="submit">Submit</a-button>
                        </a-form-item> -->
                        <a-form-item :wrapper-col="{ ...layout.wrapperCol, offset: 8 }">
                            <a-button type="primary" html-type="button" @click="backToList">Back to List</a-button>
                        </a-form-item>
                    </a-form>
                </a-col>
            </a-row>
        </template>
    </MainLayout>
</template>
<script>
import { defineComponent, reactive, onMounted, computed, watch } from 'vue';
import { useRoute, useRouter  } from 'vue-router';
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
        const router = useRouter();
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
            product: {
                name: '',
                description: '',
                quantity: 0,
                price: 0,
                product_category_name: ''
            },
        });

        onMounted(() => {
            // get product categories
            store.dispatch('getProductDetails', { id: route.params.id });
        })

        watch(productDetails, async (newProduct, oldProduct) => {
            if (!_.isUndefined(newProduct) && !_.isNull(newProduct)) {
                formState.product = {...newProduct, ...{
                    product_category_name:
                        !_.isUndefined(newProduct.product_category)
                        && !_.isNull(newProduct.product_category.name)
                        ? newProduct.product_category.name : ''
                }};
            }
        });

        const backToList = () => {
            // redirect after getting user details
            router.push({ 'name' : 'product-list' });
        }

        const onFinish = values => {
            console.log('Success:', values);
        };

        return {
            formState,
            onFinish,
            layout,
            backToList,
            validateMessages,
        };
    },
});
</script>