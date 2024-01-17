<script setup>
import { RouterView } from 'vue-router'
import login from './views/login.vue'

let token = localStorage.getItem('token')
</script>

<template>
  <div v-if="token !== null" id="content">
    <div id="nav">
      <div class="container">
        <nav class="navbar">
          <router-link :to="{name:'list'}" class="btn btn-warning">Product Manager</router-link>
          <router-link :to="{name:'batch'}" class="btn btn-warning">Batch update</router-link>
        </nav>
      </div>
    </div>
    <FlashMessage></FlashMessage>
    <RouterView/>
  </div>
  <div v-else id="login">
    <login></login>
  </div>
</template>