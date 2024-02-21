import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
import {useAuthStore} from "@/store";

import Home from '@/components/Home.vue';
import LoginAndRegister from '@/components/LoginAndRegister.vue';
import ForgotPassword from '@/components/ForgotPassword.vue';
import ResetPassword from '@/components/ResetPassword.vue';
import Dashboard from '@/components/Dashboard.vue';
import UserInformations from '@/components/UserInformations.vue';

const routes: Array<RouteRecordRaw> = [
    { path: '/', name: 'Home', component: Home },
    { path: '/home', name: 'HomeAlias', component: Home },
    { path: '/loginAndRegister', name: 'LoginAndRegister', component: LoginAndRegister },
    { path: '/forgot-password', name: 'ForgotPassword', component: ForgotPassword },
    { path: '/reset_password', name: 'ResetPassword', component: ResetPassword },
    { path: '/dashboard', name: 'Dashboard', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/user-informations/:id', name: 'UserInformations', component: UserInformations, props: true }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth) {
        const authStore = useAuthStore();
        if (authStore.token) {
            next();
        } else {
            next({ name: 'LoginAndRegister' });
        }
    } else {
        next();
    }
});

export default router;
