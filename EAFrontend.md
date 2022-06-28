
# VUE Frontend

![Vue Architectture](https://sublimedatasys.com/wp-content/uploads/2018/06/vue-1.png))


## Backend API Preparation
1. Update `VerifyCsrfToken.php` (app/Http/VerifyCsrfToken.php)
    ```php
    <?php
    namespace App\Http\Middleware;
    use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

    class VerifyCsrfToken extends Middleware
    {
        /**
         * The URIs that should be excluded from CSRF verification.
         *
         * @var array<int, string>
         */
        protected $except = [
            //***** Add the backend API URL: *****//
            'http://127.0.0.1:8000/api/*',
        ];
    }
    ```
    
2. Update `api.php` (routes/api.php)
   ```php
   <?php

    use App\Http\Controllers\EastudentsController;
    use App\Http\Controllers\AuthApiController;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;

    /*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
    */

    Route::post('register', [AuthApiController::class, 'register']);
    Route::post('login', [AuthApiController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function(){
        Route::post('logout', [AuthApiController::class, 'logout']);
        Route::get('user', function(Request $request){
          return $request->user();
        });

        //eastudents APIs Require Token Authentication
        //
    }); 

        
    Route::resource('eastudents', EastudentsController::class);

    //Practice
    Route::get('eastudents/major/{major}', [EastudentsController::class, 'major']);
    Route::get('eastudents/faculty/{faculty}', [EastudentsController::class, 'faculty']);

   ```
   
3. Start Apache web server and mySQL server on MAMP/XAMP
4. Run backend API on vscode
    - cd eabackend
    - Check routes : php artisan route:list
    - php artisan serve

## Create a vue project
> **At terminal in the VSCode**
 
1. Create Vue project
    - `npm create vite@latest eafrontend`
    - `cd eafrontend`
    - Install all required packages for Vue project (only for the first time)
      - `npm install`
    - Install Bootstrap Jquery and Axios
      - `npm install axios bootstrap` 
   - Install Vue-Router
      - `npm install vue-router@latest`
2. Run Vue porject
    - `npm run dev`


### Vue Life Cycle
![Vue Life Cycle](https://dltqhkoxgn1gx.cloudfront.net/img/posts/how-to-use-lifecycle-hooks-in-vue3-1.png)

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
            path: '/:catchAll(.*)',
            redirect: '/signin'
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
    
3. Edit `App.vue`
    ```vue
    <template>
        <div id='app'>
            <div id='nav'>
                <router-link to="/hello"><button type="button" class="btn btn-outline-success">Hello World</button></router-link>  |  
            </div>
            <br>
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
        color: #227e63;
        margin-top: 60px;
    }
    </style>
    ```

4. Edit `HelloWorld.vue`
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
    
### MVVM Paradigm
![MVVM paradigm 1](https://giselamedina1.gitbooks.io/vue-js/content/assets/import1.png)

![MVVM paradigm 2](https://giselamedina1.gitbooks.io/vue-js/content/assets/import0.png)
    
    
## READ/ADD/DELETE/UPDATE student list page
1. Create `views` folder and create `Students.vue` (views/Students.vue)
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
            <table class="table table-hover">
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
                            <button class="btn btn-xs btn-danger" data-toggle="modal" data-bs-target="#exampleModal" >Delete</button>
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
    - Create `AddStudent.vue`
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
          <button type="submit" class="btn btn-primary full-width" @click="addToAPI">Add Student</button>
          &nbsp;
          <router-link to="/">
            <button class="bbtn btn-warning full-width">Go to Student List Page</button>  
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
    - Update `index.js` (routers/index.js)
    ```js
    import { createRouter, createWebHistory } from 'vue-router'
    //import Major from '../views/Major.vue'
    import HelloWorld from '../components/HelloWorld.vue'
    import Students from '../views/Students.vue'
    import AddStudent from '../views/AddStudent.vue'

    const routes = [
        {
            path: '/',
            redirect: '/students'
        }, 
        {
            path: '/:catchAll(.*)',
            redirect: '/signin'
         },
        // {
        //   path: '/major',
        //   name: 'Major',
        //   component: Major
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
    - Edit `Students.vue`
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
            <table class="table table-hover">
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
                              <button class="btn btn-xs btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" @click="DELETE(astudent.id)">Delete</button>
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
    - Create `UpdateStudent.vue` (views/UpdateStudent.vue)
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
                <button type="submit" class="btn btn-primary full-width" @click="updateToAPI">Update Student</button>
                <button class="btn btn-warning full-width">Back to Student List Page</button>
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
    - Update `index.js` (routers/index.js)
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
        {
          path: '/:catchAll(.*)',
          redirect: '/signin'
        },
        // {
        //   path: '/major',
        //   name: 'Major',
        //   component: Major
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
    - Update `Students.vue`
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
            <table class="table table-hover">
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
                            &nbsp;
                            <router-link to="/">
                              <button class="btn btn-xs btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" @click="DELETE(astudent.id)">Delete</button>
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

## Practice: Create the student list page that filter according to selected major
[Vue Frontend Practice](https://github.com/lalitanar/mvcTutorial/blob/3d8c5cab77af05dff46502b00ce34a9a328a1c6e/practice/frontend_practice.pdf)


# Vue with Authentication

### Backend Preparation
1. Update `api.php` (routes/api.php)
    ```php
    <?php

    use App\Http\Controllers\EastudentsController;
    use App\Http\Controllers\AuthApiController;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;

    /*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
    */

    Route::post('register', [AuthApiController::class, 'register']);
    Route::post('login', [AuthApiController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function(){
        Route::post('logout', [AuthApiController::class, 'logout']);
        Route::get('user', function(Request $request){
          return $request->user();
        });

        //eastudents APIs Require Token Authentication
        Route::resource('eastudents', EastudentsController::class);

    }); 

        //eastudents APIs Require Token Authentication
        //Route::resource('eastudents', EastudentsController::class);

        //Practice
        Route::get('eastudents/major/{major}', [EastudentsController::class, 'major']);
        Route::get('eastudents/faculty/{faculty}', [EastudentsController::class, 'faculty']);

    ```

### Cookie Preparation
1. Install packake `vue-cookies` under the vue project
    - `npm install vue-cookies`
2. Edit `main.js`
    ```js
    import { createApp } from 'vue'
    import App from './App.vue'
    import router from './router'
    import 'bootstrap'
    import 'bootstrap/dist/css/bootstrap.min.css'
    import vueCookies from 'vue-cookies'
    
    createApp(App).use(router).use(vueCookies).mount('#app')
    ```
    
## SignIn
1. Create `SignIn.vue` (views/SignIn.vue)
    ```vue
    <template>
        <div class="container">
            <h1>Signin</h1>
            <br><br>
            <div class="row">
                <div class="col-lg-3"/>
                <div class="col-lg-6">
                    <input type="email" v-model="formData.email" class="form-control" placeholder="email"/> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="password" v-model="formData.password" class="form-control" placeholder="password"/>
                    <br> 
                    <button class="btn btn-success btn-block full-width" @click="signIn">Sign In</button>
                    <br>
                    <font color="red">{{ error }}</font>
                </div>
                <div class="col-lg-3"/>
            </div>
        </div>
    </template>

    <script>
    import axios from 'axios'

    export default {
      name: 'SignIn',
      data () {
        return {
            formData: {
                email: '',
                password: ''
            },
            xhrRequest: false,
            errorMessage: "",
            successMessage: ""
        }
      },
      methods: {
        signIn () {
            this.errorMessage = "";
            this.successMessage = "";
            axios.post('http://127.0.0.1:8000/api/login', this.formData)
            .then((response)=>{
                console.log(response.data)

                //Use Web storage
                //localStorage.setItem('token', response.data.token);

                //Use Cookies
                this.$cookies.set('token', response.data.token, '6000s')
                this.$router.push('/');

            })
            .catch((error)=>{
                console.log(error.response);
                this.error = error.response
            })
        }
      },
      created () {}
    }
    </script>

    <!-- Add "scoped" attribute to limit CSS to this component only -->
    <style scoped>
    h1,
    h2 {
      font-weight: normal;
    }

    ul {
      list-style-type: none;
      padding: 0;
    }

    li {
      margin: 0 10px;
    }

    a {
      color: #42b983;
    }
    </style>
    ```
    
2. Edit `index.js` (routers/index.js)
    ```js
    import { createRouter, createWebHistory } from 'vue-router'
    // import Major from '../views/Major.vue'
    import HelloWorld from '../components/HelloWorld.vue'
    import Students from '../views/Students.vue'
    import AddStudent from '../views/AddStudent.vue'
    import UpdateStudent from '../views/UpdateStudent.vue'
    import SignIn from '../views/SignIn.vue'

    const routes = [
        {
          path: '/',
          redirect: '/students'
        }, 
        {
          path: '/:catchAll(.*)',
          redirect: '/signin'
        },
        // {
        //   path: '/major',
        //   name: 'Major',
        //   component: Major
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
          path: '/signin',
          name: 'SignIn',
          component: SignIn
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
    
## Sign Out
1. Edit `App.vue`
    ```vue
    <template>
      <!-- <img alt="Vue logo" src="./assets/logo.png" />
      <HelloWorld msg="Hello Vue 3.0 + Vite" /> -->
      <div id='app'>
        <div id='nav'>
             | <router-link to="/students"><button type="button" class="btn btn-outline-success">List of Students</button></router-link> | 
            <!-- <router-link to="/major">Major</router-link> |   -->
            <router-link to="/hello"><button type="button" class="btn btn-outline-success">Hello World</button></router-link>  |  
             <button type="button" class="btn btn-outline-success" @click="logout()">Logout</button> | 
        </div>
        <br>
        <router-view/>
      </div>
    </template>
    <script>
    export default {
      name: 'App',
      methods : {
        logout() {
          //Remove Web storage
          //localStorage.clear();

          //Romove Cookies
          $cookies.remove('token')

          window.location.reload()
        }
      }
    }
    </script>
    <style>
    #app {
      font-family: Avenir, Helvetica, Arial, sans-serif;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      text-align: center;
      color: #227e63;
      margin-top: 60px;
    }

    </style>

    ```
    
## Sign Up
1. Create `SignUp.vue` (views/SignUp.vue)
    ```vue
    <template>
        <div class="container">
            <h1>Sign Up</h1>
            <br><br>
            <div class="row">
                <div class="col-lg-3"/>
                <div class="col-lg-6">
                    <input type="text" v-model="formData.name" class="form-control" placeholder="Name"/> 
                    <br>
                    <input type="email" v-model="formData.email" class="form-control" placeholder="email"/> 
                    <br>
                    <input type="password" v-model="formData.password" class="form-control" placeholder="password"/>
                    <br>
                    <input type="password" v-model="formData.password_confirmation" class="form-control" placeholder="Confirm password"/>
                    <br> 
                    <button class="btn btn-success btn-block full-width" @click="signIn">Sign In</button>
                    <br>
                    <font color="red">{{ errorMessage }}</font>
                </div>
                <div class="col-lg-3"/>
            </div>
        </div>
    </template>

    <script>
    import axios from 'axios'
    export default {
      name: 'SignIn',
      data () {
        return {
            formData: {
                name: '',
                email: '',
                password: '',
                password_confirmation : ''
            },
            xhrRequest: false,
            errorMessage: "",
            successMessage: ""
        }
      },
      methods: {
        signIn () {
            this.errorMessage = "";
            this.successMessage = "";
            axios.post('http://127.0.0.1:8000/api/register', this.formData)
            .then((response)=>{
                console.log(response.data)

                //Use Web storage
                //localStorage.setItem('token', response.data.token);

                //Use Cookies
                this.$cookies.set('token', response.data.token, '6000s')

                this.$router.push('/');

            })
            .catch((error)=>{
                console.log(error.response);
                this.errorMessage = error.response
            })
        }
      },
      created () {}
    }
    </script>

    <!-- Add "scoped" attribute to limit CSS to this component only -->
    <style scoped>
    h1,
    h2 {
      font-weight: normal;
    }

    ul {
      list-style-type: none;
      padding: 0;
    }

    li {
      margin: 0 10px;
    }

    a {
      color: #42b983;
    }
    </style>
    ```
2. Edit `index.js` (routers/index.js)
    ```js
    import { createRouter, createWebHistory } from 'vue-router'
    // import Major from '../views/Major.vue'
    import HelloWorld from '../components/HelloWorld.vue'
    import Students from '../views/Students.vue'
    import AddStudent from '../views/AddStudent.vue'
    import UpdateStudent from '../views/UpdateStudent.vue'
    import SignIn from '../views/SignIn.vue'
    import SignUp from '../views/SignUp.vue'

    const routes = [
        {
          path: '/',
          redirect: '/students'
        }, 
        {
          path: '/:catchAll(.*)',
          redirect: '/signin'
        },
        // {
        //   path: '/major',
        //   name: 'Major',
        //   component: Major
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
          path: '/signin',
          name: 'SignIn',
          component: SignIn
        },
        {
          path: '/signup',
          name: 'SignUp',
          component: SignUp
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

## Update `Students.vue / AddStudents.vue / UpdateStudent.vue` for calling API with valid TOKEN
1. Edit `Students.vue`
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
            <table class="table table-hover">
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
                            &nbsp;
                            <router-link to="/">
                              <button class="btn btn-xs btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" @click="DELETE(astudent.id)">Delete</button>
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
        created (){
          //Check Web storage
          //if (localStorage.getItem('token') === null) {
          //    this.$router.push('/signin');
          //}

         //Check Cookies
          if ($cookies.get('token') === null) {
            this.$router.push('/signin');
          }
        },
        mounted () {
            //Use Web storage
            /* axios.get('http://127.0.0.1:8000/api/eastudents',{ headers: { Authorization: 'Bearer '+localStorage.getItem('token')}})
            .then((response)=>{
                console.log(response.data)
                this.Students = response.data[1]
            })
            .catch((error)=>{
                console.log(error)
            }) */

            //Use cookies
            axios.get('http://127.0.0.1:8000/api/eastudents',{ headers: { Authorization: 'Bearer '+ $cookies.get('token')}})
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

            //Use Web storage
            /* await axios.delete(url, { headers: { Authorization: 'Bearer '+ localStorage.getItem('token')}})
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
            //this.$router.replace('/') */

            //Use cookies
            await axios.delete(url, { headers: { Authorization: 'Bearer '+ $cookies.get('token')}})
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
2. Edit `AddStudent.vue`
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
          <button type="submit" class="btn btn-primary full-width" @click="addToAPI">Submit</button>
          &nbsp;
          <router-link to="/">
            <button class="btn btn-warning full-width">Go to Student List Page</button>  
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
      created (){
          //Check Web storage
          /* if (localStorage.getItem('token') === null) {
              this.$router.push('/signin');
          } */

          //Check Cookies
          if ($cookies.get('token') === null) {
            this.$router.push('/signin');
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

            //Use Web storage
            /* axios.post('http://127.0.0.1:8000/api/eastudents',newStudent,{ headers: { Authorization: 'Bearer '+localStorage.getItem('token')}})
            .then((response)=> {
                console.log(response.data)
            })
            .catch((error)=> {
                console.log(error)
            }) */

            //Use Cookies
            axios.post('http://127.0.0.1:8000/api/eastudents',newStudent,{ headers: { Authorization: 'Bearer '+$cookies.get('token')}})
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
3. Edit `UpdateStudent.vue`
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
          <br>
            <router-link to="/">
                <button type="submit" class="btn btn-primary full-width" @click="updateToAPI">Submit</button>
            </router-link>
            &nbsp;
            <router-link to="/">
                <button class="btn btn-warning full-width">Back to Student List Page</button>
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
      created (){
          //Check Web storage
          /* if (localStorage.getItem('token') === null) {
              this.$router.push('/signin');
          } */

          //Check Cookies
          if ($cookies.get('token') === null) {
            this.$router.push('/signin');
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

          //Use Web storage
          /* await axios.put('http://127.0.0.1:8000/api/eastudents/'+this.$route.params.studentId, newStudent, { headers: { Authorization: 'Bearer '+localStorage.getItem('token')}})
          .then((response)=>{
                console.log(response)
                window.location.reload()
            })
            .catch((error)=>{
                console.log(error)
            }) */

            //Use cookies
            await axios.put('http://127.0.0.1:8000/api/eastudents/'+this.$route.params.studentId, newStudent, { headers: { Authorization: 'Bearer '+$cookies.get('token')}})
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
        //Use Web storage
        /* axios.get('http://127.0.0.1:8000/api/eastudents/'+this.$route.params.studentId, { headers: { Authorization: 'Bearer '+localStorage.getItem('token')}})
        .then((response)=>{
                console.log(response.data)
                this.Student = response.data
            })
            .catch((error)=>{
                console.log(error)
            }) */

        //Use cookies
        axios.get('http://127.0.0.1:8000/api/eastudents/'+this.$route.params.studentId, { headers: { Authorization: 'Bearer '+$cookies.get('token')}})
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
    
## VueX

![VueX](https://giselamedina1.gitbooks.io/vue-js/content/assets/importA-11.png)

![VueX Comparison](https://www.sinocalife.com/wp-content/uploads/2020/09/with-or-without-vuex.png)
