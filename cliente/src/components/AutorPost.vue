<template>
  <div class="AutorPost">
      <div class="col s2">
              <router-link :to="`/profile/${id}/${autor.replace(/\s/g,'')}`">
                  <img :src="perfil" alt="" class="responsive-img"></router-link>
                </div>
                <div class="col s10">
                <span class="black-text">
                  <small style="text-decoration: none; !important">
                    <router-link :to="`/profile/${id}/${autor.replace(/\s/g,'')}`">
                      <strong>{{ autor || 'Anonimo' }}</strong> </router-link>  - <small> {{ data || Date()}} </small> </small>
                </span>          
        </div>
        <!-- <div class="row">
          <div class="col s12">
             <ul class="collection">
                <li class="collection-item avatar">
                   <router-link :to="`/profile/${id}/${autor.replace(/\s/g,'')}`">
                    <img :src="perfil" alt="user.image" class="circle">
                    <span class="title"><strong>{{ autor || 'Anonimo'}}</strong></span>
                  </router-link>- <small class="black-text"> {{ data || Date()}} </small> </small>
                </li>
              </ul>
          </div>
        </div> -->
     
  </div>
</template>

<script>
export default {
  name: 'AutorPost',
  props: ['autor', 'data', 'perfil', 'id']
};
</script>

<style>

</style>
