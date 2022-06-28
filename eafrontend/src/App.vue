<template>
  <!-- <img alt="Vue logo" src="./assets/logo.png" />
  <HelloWorld msg="Hello Vue 3.0 + Vite" /> -->
  <div id='app'>
    <div id='nav'>
         | <router-link to="/students"><button type="button" class="btn btn-outline-success">List of Students</button></router-link> | 
        <router-link to="/major"><button type="button" class="btn btn-outline-success">Major</button></router-link>  |  
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
