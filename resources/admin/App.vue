<script setup>
import { RouterLink, RouterView } from "vue-router";
</script>

<template>
    <div class="space-y-4 pt-4">
        <h1>Hello App!</h1>
        <p><strong>Current route path:</strong> {{ $route.fullPath }}</p>
        <nav>
            <RouterLink to="/">Go to Home</RouterLink>
            <RouterLink to="/settings">Go to About</RouterLink>
        </nav>
        <main>
            <RouterView />
        </main>
    </div>
</template>
