<script setup>
import { ref } from 'vue'
import {useFetch, $post, $get} from "../request";

defineProps({
  msg: String,
})

const count = ref(0)

function doAjax() {
// console.console.log('doAjax');

return new Promise(async (resolve) => {
    try {
        // state.isLoading = true;
        // state.error = false;
        const args = {
            route: 'test'
        };
        const {data, error: fetchError} = await $get(args);
    } catch (err) {
      console.log(err);
        // state.error = err;
    } finally {
        // state.isLoading = false;
        resolve();
    }
});
}

</script>

<template>
  <div>
    <h1>{{ msg }}</h1>

    <div class="card">
      <button type="button" @click="doAjax()">count is not {{ count }}</button>
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