<template>
    <div class="hidden space-y-6 pb-16 md:block">
        <p>{{ $route.params.id }}</p>
    </div>
</template>

<script>
import Separator from "@/components/ui/separator/Separator.vue";
import SidebarNav from "@/components/partials/SidebarNav.vue";
import SecretsForm from "@/components/native/settings/SecretsForm.vue";
export default {
    name: "Settings",
    components: {
        Separator,
        SidebarNav,
        SecretsForm,
    },
    data() {
        return {
            key: "reddit_secrets",
        };
    },
    methods: {
        updateMessage(key) {
            this.key = key;
        },
    },
};
</script>

<style scoped>
/* Add your styles here */
</style>
