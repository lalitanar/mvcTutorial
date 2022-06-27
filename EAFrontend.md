
# VUE Frontend

## Create a vue project
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


## Setup project path (VRouter)

1. Edit main.js
    ```
    import { createApp } from 'vue'
    import App from './App.vue'
    import router from './router'
    import 'bootstrap'
    import 'bootstrap/dist/css/bootstrap.min.css'

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
## Add student list page
1. Create views folder and create students.vue
    ```vue
    <template>
    <div class="container">
        <h1>List of Users</h1>
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for..." v-model="search">
                <span class="input-group-btn">
                  &nbsp;&nbsp;<button class="btn btn-primary" type="button">
                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                    Search
                  </button>
                </span>
              </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->
          </div><!-- /.row -->
        </div>
        <br>
        <table class="table table-stripped table-borderes">
            <thead>
                <tr>
                <th class="center">First Name</th>
                <th class="center">Last Name</th>
                <th class="center">Major</th>
                <th class="center">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="astudent in filterStudents" v-bind:key="astudent.id">
                    <td class="text-left">{{ astudent.firstName }}</td>
                    <td class="text-left">{{ astudent.lastName }}</td>
                    <td class="text-left">{{ astudent.major }}</td>
                    <td class="text-left">
                        <button class="btn btn-xs btn-warning">Edit</button>&nbsp;
                        <button class="btn btn-xs btn-danger" data-toggle="modal" data-target=".bd-example-modal-sm" ><span class="glyphicon glyphicon-trash">Delete</span></button>
                    </td>
                </tr>
            </tbody>
        </table>
        <router-link to="/addstudent">
            <button class="btn btn-large btn-block btn-success full-width">Add Student</button>
        </router-link>
        <br>
    </div>
    </template>

    <script>
    import axios from 'axios'
    import $ from 'jquery'
    export default {
      name: 'Students',
      props: {

      },
      data() {
        return {
            Students: [],
            search: '',
            sid: ''
        }
      },
        computed : {
            filterStudents: function(){
                return this.Students.filter((student)=>{
                    return student.firstName.match(this.search)
                })
            }
        },
        mounted () {
            axios.get('http://127.0.0.1:8000/api/eastudents')
            .then((response)=>{
                console.log(response.data)
                this.Students = response.data[1]
            })
            .catch((error)=>{
                console.log(error)
            })
        },
        methods:{

        }
      }

    </script>
    ```
3. 
