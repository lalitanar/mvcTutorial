
# VUE Frontend

## Create a vue project
** At terminal **
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


## Setup project path (VRouter)

1. Edit main.js
    ```
    import { createApp } from 'vue'
    import App from './App.vue'
    import router from './router'
    import 'bootstrap'

    createApp(App).use(router).mount('#app')
    ```
    
2. Create router folder and create index.js. (router/index.js)
    ```
    import { createRouter, createWebHistory } from 'vue-router'
    import HelloWorld from '../components/HelloWorld.vue'

    const routes = [
        {
            path: '/',
            redirect: '/hello'
        }, 
        {
            path: '/hello',
            name: 'HelloWorld'                                                          ,
            component: HelloWorld
        }
        ]

    const router = createRouter({
        history: createWebHistory(),
        routes
    })
    export default router 
    ```
    
3. Edit App.vue
    ```
    <template>
        <div id='app'>
            <div id='nav'>
                <router-link to="/hello">Hello World</router-link>  |  
            </div>
            <router-view/>
        </div>
    </template>
    <script>
    export default {
        name: 'App',
        methods : {
        }
    }
    </script>
    <style>
    #app {
        font-family: Avenir, Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
        margin-top: 60px;
    }
    </style>
    ```

4. Edit Hello.vue
    ```
    <template>
        <div>
            <img alt="Vue logo" src="../assets/logo.png" />
            <h1>{{ msg }}</h1>
            <button @click="count++">count is: {{ count }}</button>
            <p>Edit <code>components/HelloWorld.vue</code> to test hot module replacement.</p>
        </div>
    </template>

    <script>
    export default {
        name: 'HelloWorld',
        data() {
            return {
                msg: 'Hello World',
                count: 0
            }
        }
    }
    </script>

    <style scoped>
    a {
        color: #42b983;
    }
    </style>

    ```

