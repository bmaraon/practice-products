import axios from 'axios';
import _ from 'lodash';

export default {
    state: () => ({
        productCategoryList: [],
    }),
    getters: {
        productCategoryList(state) {
            return state.productCategoryList;
        },
    },
    mutations: {
        setProductCategoryList(state, productCategoryList) {
            state.productCategoryList = productCategoryList
        },
    },
    actions: {
        getProductCategoryList: ({ commit, state }, data) => {
            commit('setProductCategoryList', []);
            let queryParams = '';

            _.map(data.queryParams, (value, index) => {
                if (!_.isUndefined(value) && !_.isNull(value)) {
                    queryParams += `&${index}=${value}`;
                }
            });

            return axios.get(`${window.BASE_URL}/api/product-categories?${queryParams}`, {
                headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
            }).then((response) => {
                commit('setProductCategoryList', response.data.data);
                return response;
            });
        }
    },
}