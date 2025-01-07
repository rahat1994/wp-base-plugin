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

const components = [
    {
        title: "Alert Dialog",
        href: "/docs/components/alert-dialog",
        description:
            "A modal dialog that interrupts the user with important content and expects a response.",
    },
    {
        title: "Hover Card",
        href: "/docs/components/hover-card",
        description:
            "For sighted users to preview content available behind a link.",
    },
    {
        title: "Progress",
        href: "/docs/components/progress",
        description:
            "Displays an indicator showing the completion progress of a task, typically displayed as a progress bar.",
    },
    {
        title: "Scroll-area",
        href: "/docs/components/scroll-area",
        description: "Visually or semantically separates content.",
    },
    {
        title: "Tabs",
        href: "/docs/components/tabs",
        description:
            "A set of layered sections of content—known as tab panels—that are displayed one at a time.",
    },
    {
        title: "Tooltip",
        href: "/docs/components/tooltip",
        description:
            "A popup that displays information related to an element when the element receives keyboard focus or the mouse hovers over it.",
    },
];
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
                    Overview
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
