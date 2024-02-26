import { createApp } from 'vue';
import axios from 'axios';
import router from './router';
import { createPinia } from 'pinia';

import AppHeader from '@/components/AppHeader.vue';
import {useAuthStore} from "@/store";

axios.defaults.withCredentials = true;

const app = createApp({});

const pinia = createPinia();
app.use(pinia);

const authStore = useAuthStore();
authStore.initializeFromUrl();

app.use(router);
app.component('app-header', AppHeader);
app.mount('#app');

