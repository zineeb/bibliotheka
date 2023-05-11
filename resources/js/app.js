import { createApp, ref, provide, onMounted } from 'vue';
import axios from 'axios';
axios.defaults.withCredentials = true;

import router from './router';
import AppHeader from './components/AppHeader.vue';
import AppFooter from './components/AppFooter.vue';

axios.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, (error) => {
    return Promise.reject(error);
});

const app = createApp({
    setup() {
        const userLoggedIn = ref(false);
        provide('userLoggedIn', userLoggedIn);

        onMounted(() => {
            app._context.app._container.addEventListener('user-logged-in', () => {
                userLoggedIn.value = true;
            });
        });

        return { userLoggedIn };
    }
});

app.component('app-header', AppHeader);
app.component('app-footer', AppFooter);

// Check if the URL contains a token and a redirectTo parameter
const urlParams = new URLSearchParams(window.location.search);
const token = urlParams.get('token');
const redirectTo = urlParams.get('redirectTo');

// Save the token in localStorage if it exists
if (token) {
    localStorage.setItem('token', token);
    // Remove the token and redirectTo parameter from the URL
    window.history.replaceState({}, document.title, window.location.pathname);

    // Redirect to the specified page if the redirectTo parameter exists
    if (redirectTo) {
        router.replace({ path: `/${redirectTo}` }).then(() => {
            app.use(router).mount('#app');
        });
    } else {
        app.use(router).mount('#app');
    }
} else {
    app.use(router).mount('#app');
}
