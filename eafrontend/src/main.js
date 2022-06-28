import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'
import vueCookies from 'vue-cookies'


createApp(App).use(router).use(vueCookies).mount('#app')
