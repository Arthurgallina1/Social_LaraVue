<template>
  <div class="app">
        <TheNavBar logo="SocialNet"/>
        <router-view/>
        <TheFooter/>

  </div>


</template>

<script>
import TheNavBar from '@/components/TheNavBar.vue'
import TheFooter from '@/components/TheFooter.vue'

export default {
  components: {
    TheNavBar,
    TheFooter
  }
}
</script>

<style>

#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
#nav {
  padding: 20px;
}

#nav a {
  font-weight: bold;
  color: #2c3e50;
}

#nav a.router-link-exact-active {
  color: #42b983;
}
</style>
