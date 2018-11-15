
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import VueRouter from 'vue-router';
import VueMoment from 'vue-moment';
import VueFilters from 'vue2-filters';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

var Browse = require('./components/surveys/Browse.vue');

Vue.use(VueRouter);
Vue.use(VueMoment);
Vue.use(VueFilters);

var router = new VueRouter({
    routes: [
        { path: '/', component: Browse }
    ]
});

const app = new Vue({
    el: '#app',
    router
});