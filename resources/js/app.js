require('./bootstrap');
import Vue from 'vue'

Vue.component("board", require("./components/Board.vue").default);
Vue.component("fab", require("./components/FAB.vue").default);
const app = new Vue({
    el: "#app"
});
