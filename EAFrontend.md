
#VUE Frontend

##Create a vue project
**At terminal **
1. Create Vue project
    - `npm create vite@latest eafrontend`
    - `cd eafrontend`
    - Install all required packages for Vue project
      - `npm install`
    - Run Vue porject
      - `npm run dev`


2. Install addditional packages
   - Install Bootstrap Jquery and Axios
      - `npm install axios bootstrap jquery popper.js` 
   - Install Vue-Router
      - `npm install vue-router@latest`
   - 




main.js

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import 'bootstrap'

createApp(App).use(router).mount('#app')



router/index.js
import { createRouter, createWebHistory } from 'vue-router'

const routerHistory = createWebHistory()
