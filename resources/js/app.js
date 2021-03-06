/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

//Front end accessability using Gate
import Gate from "./Gate";
Vue.prototype.$gate = new Gate(window.user);

//Moment
import moment from 'moment';

//SweetAlert
import swal from 'sweetalert2';
window.swal = swal;

//SortTable
import SortedTablePlugin from "vue-sorted-table";
Vue.use(SortedTablePlugin);

//Bootstrap Vue
import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
Vue.use(BootstrapVue)

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });
window.toast = toast;

//Simple Pagination
Vue.component('pagination', require('laravel-vue-pagination'));


//Form Handling from VForm
import { Form, HasError, AlertError } from 'vform'
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

//Vue Router
import VueRouter from 'vue-router'
//Vue ProgressBar
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '5px'
  })

  //DataTable
import { ClientTable } from 'vue-tables-2';
Vue.use(ClientTable);



Vue.use(VueRouter)

//Routes
let routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },

    { path: '/pos', component: require('./components/POS.vue').default },

    { path: '/reports', component: require('./components/Reports.vue').default },

    { path: '/invoice', component: require('./components/Invoice.vue').default },

    { path: '/users', component: require('./components/Users.vue').default },

    { path: '/inventory', component: require('./components/Inventory.vue').default },
    { path: '/product', component: require('./components/Product.vue').default },
    { path: '/branch', component: require('./components/Branch.vue').default },

    { path: '/profile', component: require('./components/Profile.vue').default },

    { path: '/developer', component: require('./components/Developer.vue').default },

    { path: '*', component: require('./components/NotFound.vue').default }

  ]
const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
})

//Filters
Vue.filter('upText', function(text){
    return text.charAt(0).toUpperCase() + text.slice(1);
});
Vue.filter('myDate', function(created){
    return moment(created).calendar();
});
Vue.filter('calendarDate', function(created){
    return moment(created).format('MMMM D, YYYY - h:mm a');
});


//HTTP Request after Creating
window.Fire = new Vue();

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

Vue.component(
    'not-found',
    require('./components/NotFound.vue').default
);

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

//App
const app = new Vue({
    el: '#app',
    router,
    data:{
        search: ''
    },
    methods:{
        searchit: _.debounce(() =>{
            Fire.$emit('searching');
        }, 1000)
    }
});
