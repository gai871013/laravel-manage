/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
/*
 Vue.component('example', require('./components/Example.vue'));

 const app = new Vue({
 el: '#app'
 });*/

require('zepto');
require('./layer');
window.FastClick = require('fastclick');
require('./mobiscroll.custom-2.16.1.min');
FastClick.attach(document.body);

window.axios = require('axios');
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.getElementById('crsf-token').getAttribute('content');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
$(function(){
    $('a').click(function(){
        $('.loaders').show(0);
    })
});