<template>
  <div class="container">
    <!-- <br>
    <div class="col s12">
    <h4 class="header center-align">Timeline</h4>
    <div class="card horizontal">
      <div class="card-image cicle">
        <img :src="user.image" width="100" height="100" class="circle">
      </div>
      <div class="card-stacked">
        <div class="card-content">
          <div class="row">
            <div class="col s4">
              <router-link :to="`/profile/${user.id}/${user.name}`">
                <span class="title"><strong>{{ user.name }}</strong></span>
              </router-link>
            </div>
            <div class="col s8">
                <span class="title"><strong>Amigos</strong></span>
                <p>amigo 1 <br> amigo 2</p>
            </div>
              
          </div>
        </div>
      </div>
    </div>
  </div> --><br>
    <div class="row">
      <div class="col s1"></div>
      <div class="col s4 mx-auto">
            <ul class="collection m7">
            <li class="collection-item avatar">
              <router-link :to="`/profile/${user.id}/${user.name}`">
                <img :src="user.image" alt="user.image" class="circle">
                <span class="title"><strong>{{ user.name }}</strong></span>
              </router-link>
              <p>X amigos </p>
              <p>Y posts</p>
              <p>Z comentarios</p>
            </li>
          </ul>
      </div>
       <div class="col s3 mx-auto center-align" >
            <ul class="collection">
              <div class="all" style="margin-top:10px; margin-bottom: 5px; ">
                   <router-link :to="`/profile/${user.id}/${user.name}`">
                <i class="material-icons mt-3">account_circle</i>
                <span class="title"><strong>{{ 'Seguindo' }}</strong></span>
              </router-link>
              <br>
              
               <router-link :to="`/profile/${amigo.id}/${amigo.name}`" v-for="amigo in amigos" :key="amigo.id">
                {{ amigo.name  }}      <br>         
               </router-link>
              </div>
          </ul>
      </div> 

        <div class="col s3 mx-auto center-align" >
            <ul class="collection">
              <div class="all" style="margin-top:10px; margin-bottom: 5px; ">
                   <router-link :to="`/profile/${user.id}/${user.name}`">
                <i class="material-icons mt-3">account_circle</i>
                <span class="title"><strong>{{ 'Seguidores' }}</strong></span>
              </router-link>
              <br>
              
               <router-link :to="`/profile/${seguidor.id}/${seguidor.name}`" v-for="seguidor in seguidores" :key="seguidor.id">
                {{ seguidor.name  }}      <br>         
               </router-link>
              </div>
          </ul>
      </div>          
    </div>
   
    
     <PublicarConteudo />
    
    <div class="row">
    <div class="col s8 m6">
      
    </div>
    <PostCard />
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
import PostCard from '@/components/PostCard'
import PublicarConteudo from '@/components/PublicarConteudo'
import { mapState, mapGetters } from 'vuex'
export default {
  name: 'HelloWorld',
  components: {
    TheCardMenu,
    PostCard,
    PublicarConteudo
  },
  data() {
    return {
      urlProximaPagina: null,
      pararScroll: false,
      amigos: {},
      seguidores: {}
      
    }
  },
  computed: {
    ...mapGetters({ getToken: 'getToken'}),
    ...mapState({ user: 'user'})

    
  },
  created() {
      this.$http.get(`${this.$urlAPI}conteudo/list`, {"headers": {"authorization":"Bearer " + this.getToken}})
          .then(res => {
            if(res.data.status){           
              this.urlProximaPagina = res.data.conteudo.next_page_url

              this.$http.get(`${this.$urlAPI}user/amigos`, {"headers": {"authorization":"Bearer " + this.getToken}})
                .then(res => {
                  if(res.data.status){
                     console.log('req 2 ')
                     this.amigos = res.data.amigos
                     this.seguidores = res.data.seguidores
                  }                 
                })
            }

            }).catch((err) => console.log(err))
  },
  methods: {
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
  
    }

}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
