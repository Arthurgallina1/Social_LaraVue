<template>
    <div class="card-menu">
           <div class="card-panel grey lighten-5 z-depth-1">
          <div class="row">
              <Profile />        
          </div>
        </div>
         <div class="card-panel grey lighten-5 z-depth-1">
          <div class="row">           
              <h5>
                    <slot/>
              </h5>         
          </div>
        </div>

    </div>
 
</template>

<script>
import Profile from '@/components/Profile'
export default {
  name: 'TheCardMenu',
  components: { Profile }
};
</script>

<style>
</style>
