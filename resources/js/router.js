import Vue from "vue";

import VueRouter from "vue-router";

import App from "./components/App.vue";

Vue.use(VueRouter);

export default new VueRouter({
    routes: [{ path: "/", component: App }],
    mode: "history"
});
