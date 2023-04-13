<template>
    <MainLayout>
        <template #content>
            <a-table
                :columns="columns"
                :row-key="record => record.id"
                :data-source="dataSource"
                :pagination="pagination"
                :loading="loading"
                @change="handleTableChange">
                <template #bodyCell="{ text, record, column}">
                    <template v-if="column.dataIndex === 'name'">
                        <router-link :to="`/products/${record.id}`">{{text}}</router-link>
                    </template>
                    <template v-if="column.dataIndex === 'product_category'">
                        {{record.product_category.name}}
                    </template>
                </template>
            </a-table>
        </template>
    </MainLayout>
</template>
<script>
import { usePagination } from 'vue-request';
import { computed, defineComponent, onMounted, h } from 'vue';
import { useStore } from 'vuex';
import _ from 'lodash';

import MainLayout from '@/components/MainLayout.vue'

export default defineComponent({
    components: {
        MainLayout
    },

    setup() {
        const categoryFilter = [];
        const store = useStore();
        const loggedInUserDetails = computed(() => store.getters.loggedInUserDetails );
        const columns = [{
            title: 'Name',
            dataIndex: 'name',
            width: '20%',
        }, {
            title: 'Description',
            dataIndex: 'description',
            width: '20%',
        }, {
            title: 'Quantity',
            dataIndex: 'quantity',
            width: '20%',
        }, {
            title: 'Price',
            dataIndex: 'price',
            width: '20%',
        }, {
            title: 'Category',
            dataIndex: 'product_category',
            filters: categoryFilter,
            width: '20%',
        }];

        onMounted(() => {
            // get user details
            if (_.isUndefined(loggedInUserDetails) || !_.has(loggedInUserDetails, 'id')) {
                store.dispatch('getLoggedInUser');
            }
        })

        function intializeData (params) {
            const queryParams = {
                page: params.page,
                perPage: params.perPage,
                categoryIds: params.product_category,
                withUserAgeRestriction: 1,
                includeWithoutAgeRestriction: 1
            };

            return store.dispatch('getProductList', { queryParams: queryParams }).then(response => {
                store.dispatch('getProductCategoryList', { queryParams: queryParams }).then(response => {
                    // format into table filter
                    _.forEach(response.data.data, productCategory => {
                        if (!_.find(categoryFilter, { value: productCategory.id })) {
                            categoryFilter.push({
                                text: productCategory.name,
                                value: productCategory.id
                            });
                        }
                    });
                });

                return response;
            });
        }

        const {
            data: dataSource,
            run,
            loading,
            current,
            totalPage,
            pageSize,
        } = usePagination(intializeData, {
            formatResult: response => response.data.data,
            pagination: {
                currentKey: 'page',
                pageSizeKey: 'perPage',
            },
        });

        const pagination = computed(() => ({
            total: totalPage,
            current: current.value,
            pageSize: pageSize.value,
        }));

        const handleTableChange = (pag, filters, sorter) => {
            console.log('filters', filters);
            run({
                perPage: pag.pageSize,
                page: pag?.current,
                sortField: sorter.field,
                sortOrder: sorter.order,
                ...filters,
            });
        };

        return {
            dataSource,
            pagination,
            loading,
            columns,
            handleTableChange,
        };
    },
});
</script>