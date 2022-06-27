
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
    ```js
    import { createApp } from 'vue'
    import App from './App.vue'
    import router from './router'
    import 'bootstrap'
    import 'bootstrap/dist/css/bootstrap.min.css'

    createApp(App).use(router).mount('#app')
    ```
    
2. Create router folder and create index.js. (router/index.js)
    ```js
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
    ```vue
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
    ```vue
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
## READ/ADD/DELETE/UPDATE student list page
1. Create views folder and create students.vue
2. _READ_ list of students 
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
2.  _ADD_ a new student
    - Create AddStudent.vue
    ```vue
    <template>
      <div class="container">
        <form>

          <div class="well">
            <h4>Add New Student</h4>
            <div class="form-group" >
              <label class="pull-left">First Name: </label>
              <input type="text" class="form-control" placeholder="First Name" v-model="Student.firstName" />
            </div>
            <div class="form-group" >
              <label class="pull-left">Last Name: </label>
              <input type="text" class="form-control" placeholder="Last Name" v-model="Student.lastName" />
            </div>
            <div class="form-group" >
              <label class="pull-left">Major: </label>
              <input type="text" class="form-control" placeholder="Major" v-model="Student.major" />
            </div>
          </div>
          <br>
          <button type="submit" class="btn btn-large btn-block btn-primary full-width" @click="addToAPI">Submit</button>&nbsp;
          <router-link to="/">
          <button class="btn btn-large btn-block btn-success full-width">Go to Student List Page</button>  
          </router-link>  
        </form>
      </div>

    </template>

    <script>
    import axios from 'axios'
    export default {
      name: 'AddStudent',
      props: {

      },
      data() {
        return {
          Student: {
            firstName: '',
            lastName: '',
            major: ''
          }
        }
      },
      methods: {
        addToAPI(){
            let newStudent = {
              firstName: this.Student.firstName,
              lastName: this.Student.lastName,
              major: this.Student.major,
            }
            console.log(newStudent)
            //alert(newStudent.firstName)
            axios.post('http://127.0.0.1:8000/api/eastudents',newStudent)
            .then((response)=> {
                console.log(response.data)
            })
            .catch((error)=> {
                console.log(error)
            })
          }
      }
    }
    </script>
    <style scoped>
    h1, h2 {
      font-weight: normal;
    }
    ul {
      list-style-type: none;
      padding: 0;
    }
    li {
      display: inline-block;
      margin: 0 10px;
    }
    a {
      color: #42b983;
    }

    </style>
    ```
    - Update routers/index.js
    ```js
    import { createRouter, createWebHistory } from 'vue-router'
    // import Home from '../views/Home.vue'
    import HelloWorld from '../components/HelloWorld.vue'
    import Students from '../views/Students.vue'
    import AddStudent from '../views/AddStudent.vue'

    const routes = [
        {
          path: '/',
          redirect: '/students'
        }, 

        // {
        //   path: '/home',
        //   name: 'Home',
        //   component: Home
        // },
        {
          path: '/students',
          name: 'Students',
          component: Students
        },
        {
            path: '/addstudent',
            name: 'AddStudent',
            component: AddStudent
          },
        {
            path: '/hello',
            name: 'HelloWorld',
            component: HelloWorld
          }
      ]

      const router = createRouter({
          history: createWebHistory(),
          routes
      })

      export default router
    ```
    
    
3.  _DELETE_ student
    - Edit Student.vue
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
                            <router-link to="/">
                              <button class="btn btn-xs btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" @click="DELETE(astudent.id)"><span class="glyphicon glyphicon-trash">Delete</span></button>
                            </router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
            <router-link to="/addstudent">
                <button class="btn btn-large btn-block btn-success full-width">Add Student</button>
            </router-link>
            <br>

          <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Delete a student record</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-danger" @click="delStudent(sid)">Delete</button>
                </div>
              </div>
            </div>
          </div>
        </div>
    </template>

    <script>
    import axios from 'axios'
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
          DELETE(id){
            this.sid = id
            //alert(id)
          },
          async delStudent(StudentId){
            var url = 'http://127.0.0.1:8000/api/eastudents/'+StudentId
            await axios.delete(url)
            .then(()=>{
              console.log('Delete userId: '+StudentId)
              //alert("Delete"+ StudentId)
            })
            .catch((error)=>{
              console.log(error)
              //alert("error"+ error)
            })
            //alert("skip"+StudentId)
            window.location.reload()
            //this.$router.replace('/')
          }
        }
      }

    </script>
    ```
4.  _EDIT_ a student
    - Create UpdateStudent.vue (views/UpdateStudent.vue)
    ```vue
    <template>
      <div class="container">
        <form>
          <div class="well">
            <h4>Update Student</h4>
            <div class="form-group" >
              <label class="pull-left">First Name: </label>
              <input type="text" class="form-control" placeholder="First Name" v-model="Student.firstName">
            </div>
            <div class="form-group" >
              <label class="pull-left">Last Name: </label>
              <input type="text" class="form-control" placeholder="Last Name" v-model="Student.lastName">
            </div>
            <div class="form-group" >
              <label class="pull-left">Major: </label>
              <input type="text" class="form-control" placeholder="Major" v-model="Student.major">
            </div>
          </div>
            <router-link to="/">
                <button type="submit" class="btn btn-large btn-block btn-primary full-width" @click="updateToAPI">Submit</button>
                <button class="btn btn-large btn-block btn-success full-width">Back to Student List Page</button>
            </router-link>
        </form>
      </div>  
    </template>

    <script>
    import axios from 'axios'
    export default {
      name: 'UpdateStudent',
      props: {

      },
      data() {
        return {
            Student: {
            firstName: '',
            lastName: '',
            major: '',
            status : 1
          }
        }
      },
      methods: {
        async updateToAPI(){
          console.log(this.$route.params.studentId)
          let newStudent = {
              firstName: this.Student.firstName,
              lastName: this.Student.lastName,
              major: this.Student.major,
              status : 1
          }
          console.log(newStudent)
          //alert("data")
          await axios.put('http://127.0.0.1:8000/api/eastudents/'+this.$route.params.studentId, newStudent)
          .then((response)=>{
                console.log(response)
                window.location.reload()
            })
            .catch((error)=>{
                console.log(error)
            })
        }
      },
      mounted() {
        axios.get('http://127.0.0.1:8000/api/eastudents/'+this.$route.params.studentId)
        .then((response)=>{
                console.log(response.data)
                this.Student = response.data
            })
            .catch((error)=>{
                console.log(error)
            })
      }
    }
    </script>
    <style scoped>
    h1, h2 {
      font-weight: normal;
    }
    ul {
      list-style-type: none;
      padding: 0;
    }
    li {
      display: inline-block;
      margin: 0 10px;
    }
    a {
      color: #42b983;
    }
    </style>
    ```
    - Update index.js
    ```js
    import { createRouter, createWebHistory } from 'vue-router'
    // import Home from '../views/Home.vue'
    import HelloWorld from '../components/HelloWorld.vue'
    import Students from '../views/Students.vue'
    import AddStudent from '../views/AddStudent.vue'
    import UpdateStudent from '../views/UpdateStudent.vue'

    const routes = [
        {
          path: '/',
          redirect: '/students'
        }, 

        // {
        //   path: '/home',
        //   name: 'Home',
        //   component: Home
        // },
        {
          path: '/students',
          name: 'Students',
          component: Students
        },
        {
          path: '/addstudent',
          name: 'AddStudent',
          component: AddStudent
        },
        {
          path: '/updatestudent/:studentId',
          name: 'UpdateStudent',
          component: UpdateStudent
        },
        {
          path: '/hello',
          name: 'HelloWorld',
          component: HelloWorld
        }
      ]

      const router = createRouter({
          history: createWebHistory(),
          routes
      })

      export default router
    ```
    - Update Students.vue
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
                            <router-link :to="{ path: 'studentupdate', name: 'UpdateStudent', params:{studentId: astudent.id} }">
                              <button class="btn btn-xs btn-warning">Edit</button>
                            </router-link>

                            <router-link to="/">
                              <button class="btn btn-xs btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" @click="DELETE(astudent.id)"><span class="glyphicon glyphicon-trash">Delete</span></button>
                            </router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
            <router-link to="/addstudent">
                <button class="btn btn-large btn-block btn-success full-width">Add Student</button>
            </router-link>
            <br>

          <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Delete a student record</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-danger" @click="delStudent(sid)">Delete</button>
                </div>
              </div>
            </div>
          </div>
        </div>
    </template>

    <script>
    import axios from 'axios'
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
          DELETE(id){
            this.sid = id
            //alert(id)
          },
          async delStudent(StudentId){
            var url = 'http://127.0.0.1:8000/api/eastudents/'+StudentId
            await axios.delete(url)
            .then(()=>{
              console.log('Delete userId: '+StudentId)
              //alert("Delete"+ StudentId)
            })
            .catch((error)=>{
              console.log(error)
              //alert("error"+ error)
            })
            //alert("skip"+StudentId)
            window.location.reload()
            //this.$router.replace('/')
          }
        }
      }

    </script>
    ```
