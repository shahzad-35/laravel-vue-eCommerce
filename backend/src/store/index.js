import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";
import * as actions from "./actions";
import * as mutations from "./mutations";

const storedData = sessionStorage.getItem("user");
const parsedData = sessionStorage.getItem("user") ? JSON.parse(sessionStorage.getItem("user")) : {};

const store = createStore({
    state: {
        user: {
            token: sessionStorage.getItem("TOKEN") || (parsedData.token ? parsedData.token : ""),
            data: parsedData || {},
        },
        products: {
            loading: false,
            data: [],
            links: [],
            from: null,
            to: null,
            page: 1,
            limit: null,
            total: null,
        },
    },
    getters: {},
    actions,
    mutations,
    plugins: [
        createPersistedState({
            key: "user",
            paths: [],
            reducer: (persistedState) => {
                return {
                    data: persistedState.user.data,
                };
            },
        }),
    ],
});

export default store;
