import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import axios from 'axios';
// import './bootstrap'; 
// import '../css/app.css';

const app = createApp(App);

axios.defaults.baseURL = '/api';
axios.defaults.headers.common['Accept'] = 'application/json';

app.config.globalProperties.$axios = axios;

app.use(router);
app.use(Toast);
app.mount('#app');
