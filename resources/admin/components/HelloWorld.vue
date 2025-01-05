<script setup>
import { ref } from 'vue'
defineProps({
  msg: String,
})

const count = ref(0)

function doAjax(){

  console.log(wpbaseplugin.ajaxurl + '/wp-json/wp/v2');
  fetch(wpbaseplugin.url + '/wp-json/wp/v2')
  .then(response => response.json())
  .then(json => console.log(json))
}

</script>

<template>
  <div>
    <h1>{{ msg }}</h1>

    <div class="card">
      <button type="button" @click="doAjax">count is not {{ count }}</button>
      <p>
        Edit
        <code>components/HelloWorld.vue</code> to test HMR + X
      </p>
    </div>

    <p>
      Check out
      <a href="https://vuejs.org/guide/quick-start.html#local" target="_blank"
        >create-vue</a
      >, the official Vue + Vite starter
    </p>
    <p>
      Install
      <a href="https://github.com/johnsoncodehk/volar" target="_blank">Volar</a>
      in your IDE for a better DX
    </p>
    <p class="read-the-docs">Click on the Vite and Vue logos to learn more</p>
  </div>
</template>

<style scoped>

.card{
  margin-left: auto;
  margin-right: auto;
}
.read-the-docs {
  color: #888;
}
</style>