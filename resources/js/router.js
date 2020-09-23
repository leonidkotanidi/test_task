import Vue from "vue";
import VueRouter from "vue-router";
import Items from "./components/pages/index";


Vue.use(VueRouter)

export default new VueRouter({
    routes: [
        {path: '/', component: Items},
    ],
    mode: 'hash'
})
