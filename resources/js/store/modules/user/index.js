import axios from 'axios';

export default {
    state: () => ({
        loggedInUserDetails: {}
    }),
    getters: {
        loggedInUserDetails(state) {
            return state.loggedInUserDetails;
        }
    },
    mutations: {
        setLoggedInUserDetails(state, userDetails) {
            state.loggedInUserDetails = userDetails
            console.log('setLoggedInUserDetails', userDetails);
        }
    },
    actions: {
        getLoggedInUser: ({ commit, state }, data) => {
            commit('setLoggedInUserDetails', {});

            return axios.get(`${window.BASE_URL}/api/logged-in-user`, {
                headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
            }).then(response => {
                commit('setLoggedInUserDetails', response.data.data);
                return response;
            });
        }
    },
}