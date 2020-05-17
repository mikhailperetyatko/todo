
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('post-update', require('./components/PostUpdate.vue'));
//Vue.component('report-generated', require('./components/ReportGenerated.vue'));
Vue.component('invite', require('./components/InviteEmail.vue'));
Vue.component('task-repeatability', require('./components/TaskRepeatability.vue'));
Vue.component('attach-subtask', require('./components/AttachSubtask.vue'));
Vue.component('comments', require('./components/Comments.vue'));
Vue.component('comment', require('./components/Comment.vue'));
Vue.component('file', require('./components/File.vue'));
Vue.component('my-marker', require('./components/Marker.vue'));
Vue.component('days', require('./components/Days.vue'));
Vue.component('toasts', require('./components/Toasts.vue'));

const app = new Vue({
    el: '#app'
});
