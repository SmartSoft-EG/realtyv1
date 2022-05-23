import { createApp } from "vue";
import App from "./App.vue";

import '@/assets/main.css'
import 'uno.css'

const app = createApp(App);
import { Locale } from 'vant';
import enUS from 'vant/es/locale/lang/en-US';

Locale.use('en-US', enUS);
app.config.globalProperties.$storage = import.meta.env.DEV ? 'http://realtyv1.test/storage/' : ''

Object.values(import.meta.globEager('/src/modules/*.ts')).forEach((module) => module.install?.(app))

app.mount("#app");
