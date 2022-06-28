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