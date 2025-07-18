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
import theme from './theme.json';

const app = createApp(App);
app.use(createPinia())
app.use(ElementPlus)

// Set dynamic theme colors
const setThemeColors = (colors) => {
    const root = document.documentElement;
    Object.entries(colors).forEach(([key, value]) => {
        root.style.setProperty(key, value);
    });

};
setThemeColors(theme);


app.component('BaseForm', BaseForm);
app.component('BaseFormItem', BaseFormItem);

setupRouterGuards();
app.use(router);
app.mount('#app');
