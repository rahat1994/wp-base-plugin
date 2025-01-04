<script setup>
import HelloWorld from './components/HelloWorld.vue'
import NewComponent from './components/NewComponent.vue'

const vitelogo = wpbaseplugin.url + '/public/vite.svg'
const vuelogo = wpbaseplugin.url + '/resources/assets/vue.svg'
</script>

<template>
  <div>
    <div>
      <a href="https://vitejs.dev" target="_blank">
        <img :src="vitelogo" class="logo" alt="Vite logo" />
      </a>
      <a href="https://vuejs.org/" target="_blank">
        <img :src="vuelogo" class="logo vue" alt="Vue logo" />
      </a>
    </div>
    <HelloWorld msg="Vite + Vue" />
    <NewComponent />
  </div>
</template>

<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>