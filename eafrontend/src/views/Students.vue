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