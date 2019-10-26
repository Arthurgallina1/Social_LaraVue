<template>
  <div class="container">
    <br>
    <div class="row">
      
    
      <div class="col s6">
        <div class="card-panel grey lighten-5 z-depth-1">
          <div class="row">
              <div class="col s2">
                  <!-- <router-link to='profile'/donoPagina.id>' -->
                  <router-link :to="`/profile/${donoPerfil.id}/${donoPerfil.name}`">
                     <img :src='donoPerfil.image' :alt="donoPerfil.name" class="responsive-img"> <!-- notice the "circle" class -->
                  </router-link>
                </div>
                <div class="col s10">
                <span class="black-text">
                    <strong>{{ donoPerfil.name }}</strong><br>
                    Member since {{ donoPerfil.created_at }}
                </span>    <br>
                   <button v-if="exibirFollow" @click="seguir(donoPerfil.id)" class="small btn"> {{ textBtn }} </button>
                </div>       
          </div>
        </div>
      </div>
        <div class="col s3">
        <div class="card-panel grey lighten-5 z-depth-1">
          <div class="row">
              <div class="col s2">
                </div>
                <div class="col s10">
                  Seguindo
                  <ul>
                    <router-link :to="`/profile/${amigo.id}/${amigo.name}`" v-for="amigo in amigos" :key="amigo.id"> {{ amigo.name }}<br> </router-link>
                  </ul>
                </div>       
          </div>
        </div>
      </div>

       <div class="col s3">
        <div class="card-panel grey lighten-5 z-depth-1">
          <div class="row">
              <div class="col s2">
                </div>
                <div class="col s10">
                  Seguidores
                  <ul>
                    <router-link :to="`/profile/${seguidor.id}/${seguidor.name}`" v-for="seguidor in seguidoresPerfil" :key="seguidor.id"> {{ seguidor.name }}<br> </router-link>
                  </ul>
                </div>       
          </div>
        </div>
      </div>
      
    </div>
    
    <div class="row">
    <div class="col s8 m6">
      
    </div>
    <PostPerfil />
    <!-- <div class="next-page center-align">
      <button v-if="urlProximaPagina"   @click="carregaPaginacao" class="btn blue" >More...</button>
    </div> -->
    <div v-scroll="handleScroll">

    </div>
  </div>
  </div>
</template>

<script>
/* eslint-disable */
import TheCardMenu from '@/components/TheCardMenu'
import PostPerfil from '@/components/PostPerfil'
import PublicarConteudo from '@/components/PublicarConteudo'
import { mapState, mapGetters } from 'vuex'
export default {
  name: 'HelloWorld',
  components: {
    TheCardMenu,
    PostPerfil,
    PublicarConteudo,
    
  },
  data() {
    return {
      urlProximaPagina: null,
      pararScroll: false,
      donoPerfil: { image: '', name: '', created_at: '', email: ''},
      exibirFollow: false,
      amigos: {},
      textBtn: 'FOLLOW',
      seguidoresPerfil: {}
      
    }
  },
  computed: {
    ...mapGetters({ getUser: 'getUser' , getToken: 'getToken'}),
  },
  created() {
      this.atualizaPagina();
  },
  watch: {
    '$route': 'atualizaPagina'
  },
  methods: {

    atualizaPagina(){
       let userAux = this.getUser;
        this.$http.get(`${this.$urlAPI}conteudo/pagina/list/${this.$route.params.id}`, {"headers": {"authorization":"Bearer " + this.getToken}})
          .then(res => {
            
            if(res.data.status){       
              this.urlProximaPagina = res.data.conteudo.next_page_url
              this.donoPerfil = res.data.donoPerfil
              
              if(this.donoPerfil.id != userAux.id){
                this.exibirFollow = true
              }

              this.$http.get(`${this.$urlAPI}user/amigos/${this.donoPerfil.id}`, {"headers": {"authorization":"Bearer " + this.getToken}})
                .then(res => {
                  
                  if(res.data.status){   
                    this.amigos = res.data.amigos                  
                    this.amigosLogado = res.data.amigoslogado
                    this.seguidoresPerfil = res.data.seguidores
                    this.eAmigo();
                  }                 
                })

            }

            }).catch((err) => console.log(err))
    },
    carregaPaginacao(){
      if(!this.urlProximaPagina){
        return 
      }
      this.$http.get(`${ this.urlProximaPagina}`, {"headers": {"authorization":"Bearer " + this.getToken}})
        .then(res => {
            if(res.data.status){
              this.urlProximaPagina = res.data.conteudo.next_page_url
              this.pararScroll = false
              this.$store.commit('setPaginationConteudosLT', res.data.conteudo.data)

            }

        })
        
     
    },
    handleScroll: function (event, element) {

      if(this.pararScroll){
        return 
      }
      if (window.scrollY > document.body.clientHeight - 950) {
        this.pararScroll = true;
       
        this.carregaPaginacao();
      }
    },
    seguir(id){
      
      this.$http.post(`${this.$urlAPI}user/follow`,{ id } ,{"headers": {"authorization":"Bearer " + this.getToken}})
        .then( res => {      
          if(res.data.status){
           
           this.amigosLogado = res.data.amigos
           this.seguidoresPerfil = res.data.seguidores
           this.eAmigo();
            
            
          } else {
            alert('deu pau')
          }
        })
    },
    eAmigo(){    
       for(let amigo of this.amigosLogado){
         if(amigo.id == this.donoPerfil.id){
           this.textBtn = 'UNFOLLOW'
           return
         }         
       }
      this.textBtn = 'FOLLOW'    
    }
    }

}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
