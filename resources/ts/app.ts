import { createApp } from 'vue';
import axios from 'axios';
import router from './router';
import { createPinia } from 'pinia';
import {useAuthStore} from "@/store";

// Composants
import AppHeader from '@/components/AppHeader.vue';

const app = createApp({});

const pinia = createPinia();
app.use(pinia);

app.use(router);

app.component('app-header', AppHeader);

app.use(() => {
    axios.interceptors.request.use((config) => {
        const authStore = useAuthStore();
        if (authStore.token) {
            config.headers.Authorization = `Bearer ${authStore.token}`;
        }
        return config;
    }, (error) => {
        return Promise.reject(error);
    });
});

app.mount('#app');
