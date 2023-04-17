// resources/js/app.js
import { createApp } from 'vue';
import AppHeader from './components/AppHeader.vue';
import AppFooter from './components/AppFooter.vue';
import router from './router';

const app = createApp({});

app.component('app-header', AppHeader);
app.component('app-footer', AppFooter);

app.use(router);

app.mount('#app');
