import VueRouter from 'vue-router';
import Vue from 'vue';

Vue.use(VueRouter);

import User from './user';
import Admin from './admin';

export const routes = [
    ...User,
    ...Admin,
    { path: '/404', component: require('../components/NotFound.vue').default },
    { path: '*', redirect: '/404' },
];

const router = new VueRouter({
    mode: 'history',
    routes
});

export default router;