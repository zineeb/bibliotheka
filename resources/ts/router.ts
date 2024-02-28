import {createRouter, createWebHistory, RouteRecordRaw} from 'vue-router';
import {useAuthStore} from "@/store";

import Home from '@/components/Home.vue';
import LoginAndRegister from '@/components/LoginAndRegister.vue';
import ForgotPassword from '@/components/ForgotPassword.vue';
import ResetPassword from '@/components/ResetPassword.vue';
import Dashboard from '@/components/Dashboard.vue';
import AddBook from "@components/AddBook.vue";
import UserProfile from "@components/UserProfile.vue";

const routes: Array<RouteRecordRaw> = [
    {path: '/', name: 'Home', component: Home},
    {path: '/home', name: 'HomeAlias', component: Home},
    {path: '/loginAndRegister', name: 'LoginAndRegister', component: LoginAndRegister},
    {path: '/forgot-password', name: 'ForgotPassword', component: ForgotPassword},
    {path: '/reset_password', name: 'ResetPassword', component: ResetPassword},
    {path: '/dashboard', name: 'Dashboard', component: Dashboard, meta: {requiresAuth: true}},
    {path: '/addBooks', name: 'AddBook', component: AddBook},
    {path: '/user-profile/:id', name: 'UserProfile', component: UserProfile, props: true}
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();
    if (to.meta.requiresAuth && !authStore.isLoggedIn) {
        next({ name: 'LoginAndRegister' });
    } else {
        next();
    }
});

export default router;
