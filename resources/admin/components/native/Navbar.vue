<script>
import { RouterLink } from "vue-router";
import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";
import { CloudDownload } from "lucide-vue-next";

export default {
    name: "Navbar",
    components: {
        RouterLink,
        Button,
        CloudDownload,
    },
    methods: {
        cn(...args) {
            return args.filter(Boolean).join(" ");
        },
    },
};
</script>

<template>
    <div>
        <div>
            <nav
                :class="
                    cn(
                        'flex items-center space-x-4 lg:space-x-6',
                        $attrs.class ?? ''
                    )
                "
            >
                <router-link
                    to="/"
                    class="text-sm font-medium transition-colors hover:text-primary"
                >
                    Feed list
                </router-link>
                <router-link
                    to="/settings"
                    class="text-sm font-medium transition-colors hover:text-primary"
                >
                    Settings
                </router-link>
            </nav>
            <div>
                <div class="flex items-center justify-between space-y-2">
                    <h2 class="text-3xl font-bold tracking-tight">
                        Welcome Back
                    </h2>
                    <Button>
                        <CloudDownload />
                        Export Data
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Add your styles here */
</style>
