<template>
    <RouterLink
        :to="to"
        :class="`edit-link text-sm font-thin text-${color} ${
            hasSeparator ? 'border-l-2 border-gray-300 pl-1' : ''
        }`"
    >
        <slot>
            {{ label }}
        </slot>
    </RouterLink>
</template>

<script setup>
import { RouterLink } from "vue-router";
const props = defineProps({
    to: {
        type: String,
        default: "#",
    },
    label: {
        type: String,
        default: "Edit",
    },
    color: {
        type: String,
        default: "blue-500",
    },
    hasSeparator: {
        type: Boolean,
        default: false,
    },
});
</script>

<style scoped>
/* Add any additional styles here */
</style>
