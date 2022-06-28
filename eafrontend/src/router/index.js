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