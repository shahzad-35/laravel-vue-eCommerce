
import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";
import * as actions from "./actions";
import * as mutations from "./mutations";

const storedData = localStorage.getItem("user");
const parsedData = storedData ? JSON.parse(storedData) : {};
console.log('parsedData',parsedData);
console.log('storedData',storedData);
const store = createStore({
  state: {
    user: {
      token: sessionStorage.getItem("TOKEN") || (parsedData.token ? parsedData.token : ""),
      data: parsedData.data || {},
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