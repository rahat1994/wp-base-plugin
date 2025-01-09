<script setup>
import { RouterLink, RouterView } from "vue-router";
import Navbar from "@/components/native/Navbar.vue";
</script>

<template>
    <div class="space-y-4 pt-4 px-4">
        <Navbar />
        <main>
            <RouterView />
        </main>
    </div>
</template>
