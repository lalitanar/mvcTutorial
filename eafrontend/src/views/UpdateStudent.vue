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