<template>
    <div class="container left-align">
        <br><br>
        <div class="row">
          <div class="col s6">
              <img :src="imagem.url" width="100%" height="50%">
               <form action="#" id="upld">
                        <div class="file-field input-field">
                        <div class="btn">
                            <span>Image</span>
                            <input type="file" @change="salvaImagem">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" >
                        </div>
                        </div>
                    </form>
          </div>
          <div class="col s6">
            <h3>My Profile</h3>
                    
                    <div class="input-field col s12">
                      <input id="name" type="text" class="validate" v-model='userAux.name' autocomplete="off">
                      
                    </div>
              
              
                    <div class="input-field col s12">
                      <input id="email" type="text" class="validate" v-model='userAux.email' autocomplete="off">
                    </div>
                    <div class="input-field col s12">
                      <input id="Password" type="password" class="validate" v-model='userAux.password' autocomplete="off">
                      <label for="password">Password</label>
                    </div>
            
              
                    <div class="input-field col s12">
                      <input id="CPassword" type="password" class="validate" v-model='userAux.confirmpassword' autocomplete="off">
                      <label for="password">Confirm Password</label>
                    </div>
            
              
                <button class="btn" @click="perfil"> Update profile </button>    
              </div> 
            </div>
      
    </div>
</template>

<script>
/* eslint-disable */
export default {
  name: 'Profile',
  data() {
      return {
          userAux: {},
          imagem: { url: '', type: '' }
      }
  },
  created() {
          // let { name, email, token, image } = JSON.parse(sessionStorage.getItem('user'))
          this.userAux = this.$store.getters.getUser;
          // this.user = this.$store.getters.getUser;
          this.imagem.url = this.userAux.image;
  },
  methods: {
       salvaImagem(event){
           let arquivo = event.target.files || event.dataTransfer.files;
           if(!arquivo.length){ //não exister não faz nada.
               return;
           }
           //vai ler e receber o objeto
            let reader = new FileReader();
            reader.onloadend = (e) => {
                this.imagem.url = e.target.result; 
            }
            reader.readAsDataURL(arquivo[0]); //
            let type = (arquivo[0].type).split('/');
            this.imagem.type = type[1];
           
       },

       perfil(){
        this.$http.put(`${this.$urlAPI}perfil`, {
          name: this.userAux.name,
          email: this.userAux.email,
          password: this.userAux.password,
          password_confirmation: this.userAux.confirmpassword,
          imagem: this.imagem.url,
          type: this.imagem.type
        }, {"headers": {"authorization":"Bearer " + this.$store.getters.getToken}} ).then(res => {
          // console.log(this.imagem.url)
          if(res.data.status){
                // sessionStorage.setItem('user', JSON.stringify(res.data.user))
                this.$store.commit('setUsuario',res.data.user)
                this.imagem.url = res.data.user.image;
                console.log('Perfil atualizado');
                
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

        }).catch(err => {
            console.log(err)
        })
          
      }
  },
};
</script>

<style scoped>
</style>
