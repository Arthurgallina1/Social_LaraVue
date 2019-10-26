<template>
  <div id="navbar">
      <nav :class="cor || 'green darken-3'">
      <div class="nav-wrapper container">
        <a href="/home" class="brand-logo">{{ logo }}</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
          <li  v-if="!user"><router-link to="/">Home</router-link></li>
          <!-- <li> <router-link to="/about">Social</router-link></li> -->
          <li v-if="!user"> <router-link to="/login">Login</router-link></li>
          <li v-if="!user"> <router-link to="/cadastro">Sign Up</router-link></li>
          <li v-if="user"> <router-link to="/home">Home</router-link></li>
          <li v-if="user"><router-link to="/profile">{{ user.name }}</router-link></li>
          <li v-if="user"> <a @click="logout">Logout</a></li>
          
        </ul>
      </div>
    </nav>
    
  </div>
</template>

<script>
/* eslint-disable */
import { mapState, mapGetters, mapMutations } from 'vuex'
export default {
  name: 'TheNavBar',
  props: ['logo', 'url', 'cor'],
  data() {
    return {
     // user: false
    }
  },
  computed: {
    ...mapState({ user: 'user' }),
    ...mapGetters({ getUser: 'getUser'})
  },
  methods: {
    ...mapMutations({setUsuario: 'setUsuario' }),
    logout(){
      sessionStorage.clear();
      this.setUsuario(null);
      this.$router.push('/login')
    }
  },
  created(){
    console.log('created');
    let userAux = this.getUser;
    if(userAux){
      // this.$router.push('/home')
    } else {
       this.$router.push('/login')
    }
  }
};
</script>

<style>
</style>
