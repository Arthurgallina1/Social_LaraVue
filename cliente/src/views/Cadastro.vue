<template>
  <div class="login center-align">
       <div class="login">
    <h1>Cadastro</h1>
      <div class="row">
          <div class="col s4"></div>
          <div class="col s4">
              <div class="row">
                    <div class="input-field col s12">
                      <input id="name" type="text" class="validate" v-model='usuario.name'>
                      <label for="name">Name</label>
                    </div>
              </div>
              <div class="row">
                    <div class="input-field col s12">
                      <input id="email" type="email" class="validate" v-model='usuario.email'>
                      <label for="email">Email</label>
                    </div>
              </div>
              <div class="row">
                  <div class="input-field col s12">
                    <input id="password" type="password" class="validate" v-model='usuario.password'>
                    <label for="password">Password</label>
                  </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                      <input id="password_confirmation" type="password" class="validate" v-model='usuario.password_confirmation'>
                      <label for="password">Confirm password</label>
                    </div>
                </div>   
               <div class="row">
                  <span v-if="erros.SemCadastro" class="red-text"> {{ erros.texto }} </span>
                </div>
                <button class="btn" @click="cadastro">Sign Up</button> &nbsp;
                <router-link class="btn orange" to="/login"> Sign In </router-link>
                <br><br>           
             
          </div>
      </div>
    </div>
    
     
  </div>
</template>

<script>
import { mapMutations } from 'vuex'
export default {
  name: 'Login',
  data() {
      return {   
          usuario: { name: '', email: '', password: '', password_confirmation: ''},
          erros: { SemCadastro: false, geral: false, texto: ''},

      }
  },
  methods: {
    ...mapMutations({setUsuario: 'setUsuario' }),
       cadastro(){
        this.$http.post(`${this.$urlAPI}cadastro`, {
          name: this.usuario.name,
          email: this.usuario.email,
          password:  this.usuario.password,
          password_confirmation: this.usuario.password_confirmation
        }).then(res => {
        
          // if(res.data.token){ //cadastro realizado com sucesso
          if(res.data.status){
              this.erros.SemCadastro = false;
              sessionStorage.setItem('user', JSON.stringify(res.data.user)) 
              this.setUsuario(res.data.user); 
              this.$router.push('/home')
              
          } else if (res.data.status == false && res.data.validacao) { //erro na validação
               this.erros.texto = ''
              //a lista de erros que podem retornar é um objeto, array de valores pra percorrer
              for(let erro of Object.values(res.data.errors)){
                this.erros.texto += erro+' ';
              }
              this.erros.SemCadastro = true

          } else { //erro 
              this.erros.SemCadastro = true;
              this.erros.texto = 'Erro no cadastro! Tente novamente'
             
          }

        }).catch(err => {
            this.erros.SemCadastro = true;
            this.erros.texto = 'Error, try again later.' + err;
        })
          
      }
  },
};
</script>

<style>
</style>
