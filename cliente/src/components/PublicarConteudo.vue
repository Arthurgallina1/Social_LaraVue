<template>
    <div class="comentario">
      <div class="row">
        <div class="input-field col s12">
          <label>What's hapenning?</label>
          <input id="title" type="text" class="materialize-text" v-model="conteudo.titulo" autocomplete="off">        
          <textarea id="textarea1" class="materialize-textarea" v-if="conteudo.titulo" autocomplete="off" placeholder="What's going on?" v-model="conteudo.texto"></textarea>
          <input id="title" type="text" placeholder="Link" v-if="conteudo.titulo && conteudo.texto" class="materialize-text" v-model="conteudo.link" autocomplete="off">  
          <input id="title" type="text" placeholder="Image" v-if="conteudo.titulo && conteudo.texto" class="materialize-text" v-model="conteudo.imagem" autocomplete="off">   
        </div>
         <p class="right-align">
            <button class="btn" v-if="conteudo.titulo && conteudo.texto" @click="addPublish">Publicar</button>
        </p>
      </div>
     
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
  name: 'PublicarConteudo',
  data() {
      return {
          conteudo: {
            titulo: '',
            texto: '',
            link: '',
            imagem: ''
          }
      }
  },
  computed: {
    ...mapGetters({ getToken: 'getToken'}),
    
  },
  methods: {
    addPublish(){
      // let { token } = JSON.parse(sessionStorage.getItem('user'));
      this.$http.post(`${this.$urlAPI}conteudo/add`, this.conteudo,
      { "headers" : {"authorization" : "Bearer "+this.getToken} }) //objeto inteiro e o token.
      .then(res => {
        if(res.data.status){
          this.conteudo = { titulo: '',       texto: '',       link: '',    imagem: ''   }
          this.$store.commit('setConteudoLT', res.data.conteudo.data) 

        } else if (res.data.status == false && res.data.validacao) { //erro na validação
            //   this.erros.texto = ''
            let erros = '';
              //a lista de erros que podem retornar é um objeto, array de valores pra percorrer
              for(let erro of Object.values(res.data.errors)){
                // this.erros.texto += erro+' ';
                erros += erro + ' ';
              }
            //   this.erros.SemCadastro = true
            alert(erros)
              
          }
      })
      
    }
  },
};
</script>

<style>
</style>
