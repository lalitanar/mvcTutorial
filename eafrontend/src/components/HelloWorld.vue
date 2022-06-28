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


  