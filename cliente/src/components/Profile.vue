<template> 
    <div class="profile">
        <div class="col s2">
                <img :src='user.image' :alt="user.name" class="circle responsive-img"> <!-- notice the "circle" class -->
                </div>
                <div class="col s10">
                <span class="black-text">
                    <h5><strong>{{ user.name }}</strong></h5>
                </span>          
        </div>
    </div>
     
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: 'Profile',
  data() {
      return {
          usuario: '',
      }
  },
  computed: {
      ...mapState({ user: 'user'})
  },
//   created() {
//         let user = sessionStorage.getItem('user');
//         if(user){
//             this.usuario = JSON.parse(user);
//         }
//     },
};
</script>

<style>
</style>
