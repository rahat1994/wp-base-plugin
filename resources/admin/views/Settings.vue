<template>
    <div class="hidden space-y-6 pb-16 md:block">
        <div class="space-y-0.5">
            <h2 class="text-2xl font-bold tracking-tight">Settings</h2>
            <p class="text-muted-foreground">
                Manage your account settings and set e-mail preferences.
            </p>
        </div>
        <Separator class="my-6" />
        <div
            class="flex flex-col space-y-8 lg:flex-row lg:space-x-12 lg:space-y-0"
        >
            <aside class="-mx-4 lg:w-1/5">
                <SidebarNav
                    @selectMenuItem="updateMessage"
                    :active-menu="key"
                />
            </aside>
            <div class="flex-1 lg:max-w-2xl">
                <div class="space-y-6">
                    <SecretsForm v-if="key === 'reddit_secrets'" />                    
                </div>
            </div>
        </div>
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
