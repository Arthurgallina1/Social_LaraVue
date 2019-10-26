<template>
  <div class="home">
    <MainHome />
  </div>
</template>

<script>
// @ is an alias to /src
import MainHome from '@/components/MainHome.vue'

export default {
  name: 'home',
  components: {
    MainHome
  }
}
</script>
