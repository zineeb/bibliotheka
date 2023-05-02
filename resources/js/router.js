import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue';
import LoginAndRegister from './components/LoginAndRegister.vue';
import ForgotPassword from "./components/ForgotPassword.vue";
import ResetPassword from "./components/ResetPassword.vue";
import Dashboard from "./components/Dashboard.vue";
import UserInformations from "./components/UserInformations.vue";

const routes = [
    { path: '/', component: Home },
    { path: '/home', component: Home },
    { path: '/loginAndRegister', component: LoginAndRegister },
    { path: '/forgot-password', component: ForgotPassword },
    { path: '/reset_password', component: ResetPassword },
    { path: '/dashboard', component: Dashboard, meta: { requiresAuth: false } },
    // J'ai mis false le temps que je dev le front
    { path: '/user-informations/:id', component: UserInformations }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    console.log('Route:', to);
    if (to.matched.some(record => record.meta.requiresAuth)) {
        const token = localStorage.getItem('token');
        if (token) {
            next();
        } else {
            next('/loginAndRegister');
        }
    } else {
        next();
    }
});

export default router;
