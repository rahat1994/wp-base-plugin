<script setup>
import { ref, reactive } from "vue";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import EditorSidebar from "@/components/native/editor/EditorSidebar.vue";

const state = reactive({
    isLoading: false,
    config:{
        title: {
            show: true,
            tag: 'h1',
            classes:''
        },
        description: {
            show: true,
            tag: 'p',
            classes: '',
        },
        links: {
            tag: 'ul',
            classes: '',
        },
        
    }
});


function updateMessage(newKey) {
    key.value = newKey;
}
</script>

<style scoped>
/* Add your styles here */
</style>

<template>
    <div class="hidden h-full flex-col md:flex space-y-2 py-6">
        <div class="h-full mx-2">
            <div
                class="grid h-full items-stretch gap-6 md:grid-cols-[minmax(0,1fr)_400px] border border-gray-200 rounded-lg shadow-md"
            >
                <EditorSidebar />
                <div class="md:order-1 p-4">
                    <div class="flex h-full flex-col space-y-4">
                        <h1 class="text-2xl font-bold mb-4">r/Ecommerce</h1>
                        <p class="mb-4">
                            This is a subreddit for ecommerce. You can find a
                            lot of useful information here.
                        </p>

                        <ul class="list-disc pl-5 space-y-2">
                            <li>
                                <a
                                    href="https://www.reddit.com/r/ecommerce/"
                                    class="text-blue-500 hover:underline"
                                    >r/Ecommerce</a
                                >
                            </li>
                            <li>
                                <a
                                    href="https://www.reddit.com/r/ecommerce/"
                                    class="text-blue-500 hover:underline"
                                    >r/Ecommerce</a
                                >
                            </li>
                            <li>
                                <a
                                    href="https://www.reddit.com/r/ecommerce/"
                                    class="text-blue-500 hover:underline"
                                    >r/Ecommerce</a
                                >
                            </li>
                            <li>
                                <a
                                    href="https://www.reddit.com/r/ecommerce/"
                                    class="text-blue-500 hover:underline"
                                    >r/Ecommerce</a
                                >
                            </li>
                            <li>
                                <a
                                    href="https://www.reddit.com/r/ecommerce/"
                                    class="text-blue-500 hover:underline"
                                    >r/Ecommerce</a
                                >
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-sm text-gray-500">
            Note: This editor does not resemble how the feed will look on your
            frontend. But it will give you the opportunity to show and hide
            elements from the feed.
        </p>
    </div>
</template>
