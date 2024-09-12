require('./bootstrap');

window.Vue = require('vue').default;

import vuetify from './plugins/vuetify' // path to vuetify export

import axios from 'axios';
import VueAxios from 'vue-axios';
Vue.use(VueAxios, axios);

import VueRouter from 'vue-router';
Vue.use(VueRouter);



import Permissions from './plugins/permisos.js';
Vue.mixin(Permissions);

import fnFechas from './plugins/fechas.js';
Vue.use(fnFechas);
import globales from './plugins/globales.js';
Vue.use(globales);

import Vue from 'vue';
import store from './plugins/stores.js'




let routes = [
    
    //ELEMENTOS DEL MENU
    
    { path: '/', name: 'home', component: require('./components/dashboard/cpDashboard.vue').default },
    { path: '/biblio/:idCarpeta', name: 'biblioteca', props: { node:false },        
        component: require('./components/biblioteca/biblioteca.vue').default },
    { path: '/veritem/:id', name: 'veritem', props: { node:false },        
        component: require('./components/biblioteca/verRegistro.vue').default },

    { path: '/roles', name: 'roles', component: require('./components/seguridad/roles.vue').default },
    { path: '/segusers', name: 'segusers', component: require('./components/seguridad/usuarios.vue').default },
    { path: '/segpermisos', name: '/segpermisos', component: require('./components/seguridad/permisos.vue').default },
    { path: '/miperfil', name: '/miperfil', component: require('./components/seguridad/miperfil.vue').default },

    { path: '/buscar/:cBusqueda', name: 'buscar', props: { node:true },        
        component: require('./components/buscador/resultBuscar.vue').default },

    { path: '/tiposdocs', name: 'tiposdocs', component: require('./components/catalogos/catTipoDoc.vue').default },
    { path: '/temas', name: 'temas', component: require('./components/catalogos/catTemas.vue').default },
    { path: '/atributos', name: 'atributos', component: require('./components/catalogos/catAtributos.vue').default },
    { path: '/catpublicaciones', name: 'catpublicaciones', component: require('./components/catalogos/catPubs.vue').default },
    { path: '/tiposautor', name: 'tiposautor', component: require('./components/catalogos/catTipoAutores.vue').default },
    { path: '/asociaatrs', name: 'asociaatrs', component: require('./components/catalogos/asociaAtr.vue').default },
    

];

const router = new VueRouter({
    mode: 'history',
    //base: `/${url.pathname}/`,
    //base: '/haydee/public/',
    routes, // short for `routes: routes`
    /*scrollBehavior (to, from, savedPosition) {
        
        let scrollTo = savedPosition || { x: 0, y: 0 };
        setTimeout(() => {
            window.scrollTo(scrollTo.x, scrollTo.y);
            return scrollTo;
        }, 50);
    },*/
});



Vue.component('app', require('./components/master/app.vue').default);



const app = new Vue({
    el: '#app',
    vuetify,
    router,
    Permissions,
    store
});
