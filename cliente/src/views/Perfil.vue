<template>
  <div class="home">
    <PerfilHome :perfil="donoPerfil" />
  </div>
</template>

<script>
// @ is an alias to /src
import PerfilHome from '@/components/PerfilHome.vue'

export default {
  name: 'Perfil',
  props: ['donoPerfil'],
  components: {
    PerfilHome
  }
}
</script>
