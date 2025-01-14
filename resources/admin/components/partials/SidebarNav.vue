<script setup lang="ts">
import { Button } from "@/components/ui/button";
import { cn } from "@/lib/utils";

const emit = defineEmits(["selectMenuItem"]);

function selectMenuItem(title: string) {
    console.log("Selected menu item:", title);
    emit("selectMenuItem", title);
}

const props = defineProps<{
    activeMenu: string;
}>();

const sidebarNavItems = [
    {
        title: "Reddit Secrets",
        key: "reddit_secrets",
        icon: "reddit",
    },
];
</script>

<template>
    <nav class="flex space-x-2 lg:flex-col lg:space-x-0 lg:space-y-1">
        <Button
            v-for="item in sidebarNavItems"
            :key="item.title"
            as="a"
            variant="ghost"
            @click="selectMenuItem(item.key)"
            :class="
                cn(
                    'w-full text-left justify-start cursor-pointer',
                    item.key === props.activeMenu && 'bg-muted hover:bg-muted'
                )
            "
        >
            {{ item.title }}
        </Button>
    </nav>
</template>
<style scoped>
.active {
    background-color: #007bff;
    color: white;
}
</style>
