import axios from 'axios';
import _ from 'lodash';

export default {
    state: () => ({
        productList: [],
        productDetails: {},
    }),
    getters: {
        productList(state) {
            return state.productList;
        },
        productDetails(state) {
            return state.productDetails;
        }
    },
    mutations: {
        setProductList(state, productList) {
            state.productList = productList
        },
        setProductDetails(state, userDetails) {
            state.productDetails = userDetails
        }
    },
    actions: {
        getProductList: ({ commit, state }, data) => {
            commit('setProductList', []);
            let queryParams = '';

            _.map(data.queryParams, (value, index) => {
                if (!_.isUndefined(value) && !_.isNull(value)) {
                    queryParams += `&${index}=${value}`;
                }
            });

            return axios.get(`${window.BASE_URL}/api/products?${queryParams}`, {
                headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
            }).then((response) => {
                commit('setProductList', response.data.data);
                return response;
            });
        },
        getProductDetails: ({ commit, state }, data) => {
            commit('setProductDetails', {});

            return axios.get(`${window.BASE_URL}/api/products/${data.id}`, {
                headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
            }).then((response) => {
                commit('setProductDetails', response.data.data);
                return response;
            });
        }
    },
}