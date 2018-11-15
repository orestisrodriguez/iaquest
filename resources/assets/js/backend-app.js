
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import VueRouter from 'vue-router';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

Vue.use(VueRouter);

var Dashboard = require('./components/admin/Dashboard.vue');
var Users = require('./components/admin/Users.vue');
var Roles = require('./components/admin/Roles.vue');
var Permissions = require('./components/admin/Permissions.vue');
var Surveys = require('./components/admin/Surveys.vue');

var router = new VueRouter({
    routes: [
        { path: '/', component: Dashboard },
        { path: '/users', component: Users },
        { path: '/roles', component: Roles },
        { path: '/permissions', component: Permissions },
        { path: '/surveys', component: Surveys }
    ]
})

const app = new Vue({
    router
}).$mount('#app')