import './bootstrap';
import { createPinia } from 'pinia'
import { createApp } from 'vue';
import App from './App.vue';
import { router, setupRouterGuards } from './router';

// Element Plus imports
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import BaseForm from "@/components/FormElements/BaseForm";
import BaseFormItem from "@/components/FormElements/BaseFormItem";

const app = createApp(App);
app.use(createPinia())
app.use(ElementPlus)

app.component('BaseForm', BaseForm);
app.component('BaseFormItem', BaseFormItem);

setupRouterGuards();
app.use(router);

app.mount('#app');
