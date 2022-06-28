<template>
    <div class="container">
        <h1>List of Students by Major</h1>
        <div class="container">
          <div class="row">
            <div class="col-sm-12 col-lg-12" >
              <div class="text-center"  >
                  <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" @click="chooseMajorAPI('ICCI')" checked>
                    <label class="btn btn-outline-success" for="btnradio1">ICCI</label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off" @click="chooseMajorAPI('EGCI')">
                    <label class="btn btn-outline-success" for="btnradio2">EGCI</label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off" @click="chooseMajorAPI('EGCO')">
                    <label class="btn btn-outline-success" for="btnradio3">EGCO</label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off" @click="chooseMajorAPI('SCMA')">
                    <label class="btn btn-outline-success" for="btnradio4">SCMA</label>
                  </div>
              </div><!-- /input-group -->
            </div><!-- /.col-lg-12 -->
          </div><!-- /.row -->
        </div>
        <br>
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
                </tr>
            </thead>
            <tbody>
                <tr v-for="astudent in filterStudents" v-bind:key="astudent.id">
                    <td class="text-left">{{ astudent.firstName }}</td>
                    <td class="text-left">{{ astudent.lastName }}</td>
                    <td class="text-left">{{ astudent.major }}</td>
                </tr>
            </tbody>
        </table>
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
        /* axios.get('http://127.0.0.1:8000/api/eastudents/major/icci',{ headers: { Authorization: 'Bearer '+localStorage.getItem('token')}})
        .then((response)=>{
            console.log(response.data)
            this.Students = response.data[1]
        })
        .catch((error)=>{
            console.log(error)
        }) */

        //Use cookies
        axios.get('http://127.0.0.1:8000/api/eastudents/major/icci',{ headers: { Authorization: 'Bearer '+ $cookies.get('token')}})
        .then((response)=>{
            console.log(response.data)
            this.Students = response.data[1]
        })
        .catch((error)=>{
            console.log(error)
        })
    },
    methods:{
      chooseMajorAPI(major){
          //alert(major)
          //Use cookies
        axios.get('http://127.0.0.1:8000/api/eastudents/major/'+major,{ headers: { Authorization: 'Bearer '+ $cookies.get('token')}})
        .then((response)=>{
            console.log(response.data)
            this.Students = response.data[1]
        })
        .catch((error)=>{
            console.log(error)
        })
      }
    }

  }

</script>