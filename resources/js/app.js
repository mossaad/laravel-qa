/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**https://github.com/arthurvasconcelos/vue-izitoast */
import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import Authorization from './authorization/authorize'; //utilize authorization

Vue.use(VueIziToast);
Vue.use(Authorization); //integrating authorization with vue

// /**(MOVED TO AUTHORIZE.JS)
//  * define the authorize function that can called in template
//  * ptototype is away to inheret class so we can add authorize function directly to component instance without injection mechanizm
//  */
// import policies from './policies';
// Vue.prototype.authorize = function (policy, model) {
//     //make sure that the user has successfully singnedIn
//     if (! window.Auth.signedIn) return false;

//     if (typeof policy == 'string' && typeof model == 'object' ) {
//         const user = window.Auth.user;

//         return policies[policy](user, model);
//         //policies hold all policies in policies.js
//         //this function will be called somthing like -> authorize('modify', answer)
//     }
// }

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
//Vue.component('user-info', require('./components/UserInfo.vue').default); //imported in Question.vue and Answer.vue
//Vue.component('answer', require('./components/Answer.vue').default); //imported in Answers.vue
//Vue.component('favorite', require('./components/Favorite.vue').default); //imported in Vote.vue
//Vue.component('accept', require('./components/Accept.vue').default); //imported in Vote.vue
//Vue.component('vote', require('./components/Vote.vue').default); //imported in Question.vue and Answer.vue
//Vue.component('answers', require('./components/Answers.vue').default); //imported in QuestionPage.vue
//Vue.component('question', require('./components/Question.vue').default); //imported in QuestionPage.vue

Vue.component('question-page', require('./pages/QusetionPage.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
