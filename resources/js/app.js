import './bootstrap';
import { createPinia } from 'pinia'
import { createApp } from 'vue';
import App from './App.vue';
import { router, setupRouterGuards } from './router';

const app = createApp(App);
app.use(createPinia())

setupRouterGuards();
app.use(router);

app.mount('#app');
