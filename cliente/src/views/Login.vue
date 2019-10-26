<template>
  <div class="login center-align">
    <div class="login">
    <h1>Login</h1>
      <div class="row">
          <div class="col s4"></div>
          <div class="col s4">
              <div class="row">
                    <div class="input-field col s12">
                    <input id="email" type="email" class="validate" v-model="usuario.email">
                    <label for="email">Email</label>
                    </div>
              </div>

              <div class="row">
                    <div class="input-field col s12">
                    <input id="password" type="password" class="validate" v-model="usuario.password">
                    <label for="password">Password</label>
                    </div>
                </div>
                <div class="row">
                  <span v-if="erros.SemCadastro" class="red-text"> {{ erros.texto }} </span>
                </div>
          
                <div class="row">
                  <button class="btn" @click="login">Login</button> &nbsp;
                  <router-link class="btn orange" to="/cadastro"> Sign Up </router-link>  
                </div>
                         
              
          </div>
      </div>
    </div>
     
  </div>
</template>

<script>
import axios from 'axios'
import { mapMutations } from 'vuex';
export default {
  name: 'Login',
  data() {
      return {
        usuario: { email: '', password: '' },
        erros: { SemCadastro: false, geral: false, texto: ''},
        
      }
  },
  
  methods: {
    ...mapMutations({setUsuario: 'setUsuario' }),
      login(){
        axios.post(`${this.$urlAPI}login`, {
          email: this.usuario.email,
          password:  this.usuario.password
        }).then(res => {
          
          // if(res.data.token){ //login com sucesso pois há um token
            if(res.data.status){ //status = true
              this.erros.SemCadastro = false;
              sessionStorage.setItem('user', JSON.stringify(res.data.user))   
              this.setUsuario(res.data.user);
              this.$router.push('/home')
              
          } else if (res.data.status == false && res.data.validacao) { //erros de validação
               this.erros.texto = ''
              //a lista de erros que podem retornar é um objeto, array de valores pra percorrer
              for(let erro of Object.values(res.data.errors)){
                this.erros.texto += erro+' ';
              }
              this.erros.SemCadastro = true
            
          } else { //login nao existe
                this.erros.SemCadastro = true;
              this.erros.texto = 'User not found!'
              
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
