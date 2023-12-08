
import { createStore } from "vuex";
import createPersistedState from "vuex-persistedstate";
import * as actions from "./actions";
import * as mutations from "./mutations";

const storedData = sessionStorage.getItem("user");
const parsedData = storedData ? JSON.parse(storedData) : {};

const store = createStore({
  state: {
    user: {
      token: sessionStorage.getItem("TOKEN") || (parsedData.token ? parsedData.token : ""),
      data: parsedData || {},
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