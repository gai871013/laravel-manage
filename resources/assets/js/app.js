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


require('./bootstrap');
require('bootstrap-validator');
// window.FastClick = require('fastclick');
// FastClick.attach(document.body);
window.layer = require('layer');
require('jquery-pjax');
window.Pace = Pace = require('pace-progress');

$.pjax.defaults.timeout = 5000;
$(document).pjax('a:not(a[target="_blank"],.no_pjax)', {
    container: 'body'
});
$(document).on('pjax:start', function () {
    layer.load(1, {shade: [0.1, '#000']});
    Pace.start();
});
$(document).on('pjax:end', function () {
    Pace.stop();
    layer.closeAll();
});
$(document).on('pjax:error', function (event, xhr) {
    layer.alert('链接错误');
});
$(document).on("pjax:timeout", function (event) {
    // 阻止超时导致链接跳转事件发生
    event.preventDefault()
});
