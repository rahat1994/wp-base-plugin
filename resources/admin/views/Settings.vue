<script setup>
import { ref } from "vue";
import Separator from "@/components/ui/separator/Separator.vue";
import SidebarNav from "@/components/partials/SidebarNav.vue";
import SecretsForm from "@/components/native/settings/SecretsForm.vue";

const key = ref("reddit_secrets");

const updateMessage = (newKey) => {
    key.value = newKey;
};
</script>

<template>
    <div class="hidden space-y-6 pb-16 md:block">
        <div class="space-y-0.5">
            <h2 class="text-2xl font-bold tracking-tight">Settings</h2>
            <p class="text-muted-foreground">Manage your reddit settings.</p>
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
                    <p v-if="key === 'data_loading_options'">
                        Should load data asyncronously
                    </p>
                    <p v-if="key === 'reddit_app_name'">
                        Application name for reddit api
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Add your styles here */
</style>
