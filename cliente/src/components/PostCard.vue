<template>
  <div class="post container" >
    
      <div class="row" v-for="conteudo in getConteudoLinhaTempo" :key='conteudo.id'>
          <!-- <div class="col s2"></div> -->
            <div class="col s12">
            <div class="card white darken-1">
              <div class="card-content white-text">
                <span class="card-title"><AutorPost :autor="conteudo.user.name"
                :perfil="conteudo.user.image" :data="conteudo.data" :id="conteudo.user.id"/></span>
                  
                <div class="row">
                  <div class="col s12 m12">
                    <div class="card">
                      <div class="card-image" v-if='conteudo.link && conteudo.link != "#"'>
                        <img :src="conteudo.link" >
                        <span class="card-title"><strong> {{ conteudo.titulo }} </strong></span>
                            </div>
                            <div class="card-content black-text">
                                <p>{{ conteudo.texto }}</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card-action">
                        <a @click="curtida(conteudo.id)" v-if="conteudo.curtiu_conteudo"><i class="material-icons"> favorite </i>{{ conteudo.total_curtidas }}</a>
                        <a @click="curtida(conteudo.id)" v-else><i class="material-icons"> favorite_border </i>{{ conteudo.total_curtidas }}</a>
                        <a @click="abrircomentario()"><i class="material-icons">comment</i>{{ }}</a>
                        <!-- <a @click="curtida(conteudo.id)"><i class="material-icons">{{ curtiu }}</i>{{ conteudo.total_curtidas }}</a> -->
                       
                      </div>
                      <div v-if="comentario" class="comentario right-align row">
                        <div class="col s1"></div>
                          <div class="col s10">
                            <input type="text" placeholder="Comment" v-model="textoComentario">
                              <button v-if="textoComentario" class="btn waves-effect waves-light orange" @click="comentar(conteudo.id)">Send it!<i class="material-icons"> send </i></button>
                          </div>                
                        </div>
                         <ul class="collection" v-for="item in conteudo.comentarios" :key="item.id">
                          <li class="collection-item avatar">
                            <img :src="item.user.image" alt="" class="circle">
                            <span class="title"><strong>{{ item.user.name }}</strong> - {{ item.data }}</span>
                            <p>{{ item.texto }}                          
                            </p>
                            <a class="secondary-content"><i class="material-icons">grade</i></a>
                          </li>
                        </ul>
                    </div>
                    
                    
              </div>
  </div>

  </div>
      
</template>

<script>
/* eslint-disable */
// import Profile from '@/components/Profile'
import AutorPost from '@/components/AutorPost'
import { mapState, mapGetters } from 'vuex'

export default {
  name: 'PostCard',
  components: {
      // Profile,
      AutorPost
  },
   data() {
    return {
      conteudos: [],
      curtiu: this.curtiu_conteudo ? 'favorite': 'favorite_border',
      //totalCurtidas: 0,
      comentario: false,
      textoComentario: '',
    }
  },
  computed: {
    ...mapState({ user: 'user '}),
    ...mapGetters({ getToken: 'getToken', getConteudoLinhaTempo: 'getConteudoLinhaTempo'}),
  },
  created() {
   
      this.$http.get(`${this.$urlAPI}conteudo/list`, {"headers": {"authorization":"Bearer " + this.getToken}})
      .then(res => {
        if(res.data.status){
         
          // this.conteudos = res.data.conteudo.data;
          this.$store.commit('setConteudoLT', res.data.conteudo.data)

        }

        }).catch((err) => console.log(err))
    
  },
  methods: {
    curtida(id){
      //teste para ver se estou na home ou no profile
      let url = '';
      if(this.$route.name == 'home'){
        url = 'curtida';

      } else {
        url = 'curtidaperfil'
      }
      console.log(url)
      this.curtiu = (this.curtiu == 'favorite_border') ? 'favorite' : 'favorite_border'
      this.$http.put(`${this.$urlAPI}conteudo/${url}/${id}`,{}, {"headers": {"authorization":"Bearer " + this.getToken}})
        .then(res => {
          if(res.data.status === true){
              // console.log(res.data)
            this.$store.commit('setConteudoLT', res.data.lista.conteudo.data)
            

          } else {
            console.log('ocorreu um problema');
            
          }
        })
      
    },

    abrircomentario(id){
    this.comentario = !this.comentario
    },

    comentar(id){
      if(!this.textoComentario){
        return ;
      }
      this.$http.post(`${this.$urlAPI}conteudo/comentario/${id}`, {
        texto: this.textoComentario
      }, {"headers": {"authorization":"Bearer " + this.getToken}})
        .then(res => {
              this.$store.commit('setConteudoLT', res.data.lista.conteudo.data) //atualiza vuex
              this.textoComentario = ''

        })
    }
  },
 
};
</script>

<style scoped>
.post {
  display: flex;
  flex-flow: column wrap;
  
}

a{
  cursor: pointer;
}

.comentario{
  padding: 10px;
  margin-bottom: 30px;
}
</style>
