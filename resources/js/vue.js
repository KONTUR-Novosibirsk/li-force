import {createApp, defineAsyncComponent} from 'vue'
import { createPinia } from 'pinia'
const pinia = createPinia()
import directives from "../admin/js/Shared/Directives/directives";
import Account from "@/components/Personal/Account.vue";
const app = () => createApp({})
const vue_apps = document.querySelectorAll('.vue_app');

vue_apps.forEach(function (el) {
    const vueApp = app();
    // Shop
    vueApp.component('CartComponent', defineAsyncComponent(() => import('./components/Cart/CartComponent.vue')))
    vueApp.component('CartDropdownComponent', defineAsyncComponent(() => import('./components/Cart/CartDropdownComponent.vue')))
    vueApp.component('CompareComponent', defineAsyncComponent(() => import('./components/Compare/CompareComponent.vue')))
    vueApp.component('SelectedComponent', defineAsyncComponent(() => import('./components/Selected/SelectedComponent.vue')))
    vueApp.component('Configurator', defineAsyncComponent( () => import('./components/Configurator/Configurator.vue')))
    vueApp.component('RadioGroupComponent', defineAsyncComponent( ()=> import('./components/Cart/RadioGroupComponent.vue')))
    vueApp.component( 'DeliveryComponent', defineAsyncComponent( () => import('./components/Cart/DeliveryComponent.vue')))
    vueApp.component( 'PaymentComponent', defineAsyncComponent( () => import('./components/Cart/PaymentComponent.vue')))
    vueApp.component('ProductSorterComponent', defineAsyncComponent(() => import('./components/Shop/ProductSorterComponent.vue')))
    vueApp.component('ProductFilterComponent', defineAsyncComponent(() => import('./components/Shop/ProductFilterComponent.vue')))

    // Personal
    vueApp.component('Account', defineAsyncComponent(() => import('./components/Personal/Account.vue')))
    vueApp.component('LoginFormComponent', defineAsyncComponent(() => import('./components/Personal/LoginFormComponent.vue')))
    vueApp.component('RegisterFormComponent', defineAsyncComponent(() => import('./components/Personal/RegisterFormComponent.vue')))
    vueApp.component('PasswordResetFormComponent', defineAsyncComponent(() => import('./components/Personal/PasswordResetFormComponent.vue')))
    vueApp.component('PasswordEditFormComponent', defineAsyncComponent(() => import('./components/Personal/PasswordEditFormComponent.vue')))


    vueApp.component('QuestionFormComponent', defineAsyncComponent(() => import('./components/Questions/QuestionFormComponent.vue')))
    vueApp.component('ReviewFormComponent', defineAsyncComponent(() => import('./components/Reviews/ReviewFormComponent.vue')))
    vueApp.component('FeedbackFormComponent', defineAsyncComponent(() => import('./components/Feedback/FeedbackFormComponent.vue')))
    vueApp.component('CallbackFormComponent', defineAsyncComponent(() => import('./components/Feedback/CallbackFormComponent.vue')))
    vueApp.use(pinia).use(directives).mount(el);
});

document.addEventListener('DOMContentLoaded', function () {

});
