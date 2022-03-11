import VueRouter from 'vue-router';
import Vue from 'vue';

Vue.use(VueRouter);

import User from './user';
import Admin from './admin';

export const routes = [
    ...Admin,
    ...User,
    { path: '/404', name: '404', component: require('../components/NotFound.vue').default },
    { path: '*', redirect: '/404' },
];

const router = new VueRouter({
    mode: 'history',
    routes
});

function loggedIn() {
    return localStorage.getItem('token') !== null;
}

router.beforeEach((to, from, next) => {
    document.title = to.meta.title ? to.meta.title : 'Điện Máy Như Ý';
    next();
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!loggedIn()) {
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            });
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;