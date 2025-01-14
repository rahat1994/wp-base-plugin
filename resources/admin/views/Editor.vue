<script setup>
import { ref, reactive, onMounted } from "vue";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { useRoute } from "vue-router";
import EditorSidebar from "@/components/native/editor/EditorSidebar.vue";
import { $get } from "@/request";
const route = useRoute();
const state = reactive({
    isLoading: false,
    config: {
        title: {
            show: true,
            tag: "h3",
            classes: "",
        },
        description: {
            show: true,
            tag: "p",
            classes: "",
        },
        list: {
            show: true,
            tag: "ul",
            classes: "",
        },
        links: {
            tag: "a",
            classes: "",
        },
    },

    data: {
        title: "Subreddit Title",
        description:
            "A demo description of your subreddit. You can find a lot of useful information here.",
        links: [
            {
                text: "How to open an online store",
                url: "https://www.reddit.com/r/ecommerce/",
            },
            {
                text: "How to setup a payment gateway",
                url: "https://www.reddit.com/r/ecommerce/",
            },
            {
                text: "How to recive payments from customers",
                url: "https://www.reddit.com/r/ecommerce/",
            },
            {
                text: "how to setup a shipping method",
                url: "https://www.reddit.com/r/ecommerce/",
            },
            {
                text: "How to market your online store",
                url: "https://www.reddit.com/r/ecommerce/",
            },
            {
                text: "How to open an online store",
                url: "https://www.reddit.com/r/ecommerce/",
            },
            {
                text: "How to setup a payment gateway",
                url: "https://www.reddit.com/r/ecommerce/",
            },
            {
                text: "How to recive payments from customers",
                url: "https://www.reddit.com/r/ecommerce/",
            },
            {
                text: "how to setup a shipping method",
                url: "https://www.reddit.com/r/ecommerce/",
            },
            {
                text: "How to market your online store",
                url: "https://www.reddit.com/r/ecommerce/",
            },
        ],
    },
});

const updateConfig = (value) => {
    state.config = value;
};

const saveConfig = (value) => {
    // state.isLoading = true;
    // makeSaveConfigRequest();
};

const makeSaveConfigRequest = () => {
    console.log(state.config);

    // Make a request to the server to save the config
    // Use the state.config object to send the data to the server
    // Use the state.data object to send the data to the server
    // Use the state.isLoading to show a loading spinner
};

function getEditorConfig() {
    return new Promise(async (resolve) => {
        try {
            state.isLoading = true;

            // await new Promise((resolve) => setTimeout(resolve, 500));

            state.error = null;
            const args = {
                route: "editor-get-feed-config",
                feed: route.params.id,
            };
            const { data, error: fetchError } = await $get(args);
            if (data && data.value) {
                if (!data.value.data.success) {
                    state.error = data.value.data.message;
                    return;
                }
                state.isLoading = false;
                console.log(data.value.data.config);
                state.config = JSON.parse(data.value.data.config);
            } else if (fetchError) {
                state.error = fetchError;
            }
        } catch (err) {
            state.error = err;
            console.log(err);
        } finally {
            state.isLoading = false;
            resolve();
        }
    });
}

onMounted(() => {
    getEditorConfig();
});
</script>

<style scoped>
/* Add your styles here */
</style>

<template>
    <div class="hidden h-full flex-col md:flex space-y-2 py-6">
        <p v-if="state.isLoading">Loading...</p>
        <div class="h-full mx-2" v-if="!state.isLoading">
            <div
                class="grid h-full items-stretch gap-6 md:grid-cols-[minmax(0,1fr)_400px] border border-gray-200 rounded-lg shadow-md"
            >
                <EditorSidebar
                    :config="state.config"
                    :feed="$route.params.id"
                    @update:config="updateConfig"
                    @save="saveConfig"
                />
                <div class="md:order-1 p-4">
                    <div class="flex h-full flex-col space-y-4">
                        <component
                            :is="state.config.title.tag"
                            v-if="state.config.title.show"
                            :class="{
                                'text-2xl font-bold mb-4':
                                    state.config.title.tag === 'h3',
                                'text-3xl font-semibold mb-3':
                                    state.config.title.tag === 'h2',
                                'text-4xl font-medium mb-2':
                                    state.config.title.tag === 'h1',
                                'text-lg font-normal mb-5':
                                    state.config.title.tag === 'h4',
                                'text-md font-light mb-6':
                                    state.config.title.tag === 'h5',
                                'text-sm font-thin mb-7':
                                    state.config.title.tag === 'h6',
                            }"
                        >
                            {{ state.data.title }}
                        </component>
                        <component
                            :is="state.config.description.tag"
                            v-if="state.config.description.show"
                            class="mb-4"
                        >
                            {{ state.data.description }}
                        </component>

                        <component
                            :is="state.config.list.tag"
                            :class="
                                state.config.list.tag === 'ul'
                                    ? 'list-disc' + ' px-5 space-y-2'
                                    : 'list-decimal' + ' px-5 space-y-2'
                            "
                        >
                            <li v-for="link in state.data.links">
                                <a
                                    :href="link.url"
                                    class="text-blue-500 hover:underline text-md"
                                    >{{ link.text }}</a
                                >
                            </li>
                        </component>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-sm text-gray-500 py-6">
            Note: This editor does not resemble how the feed will look on your
            frontend. But it will give you the opportunity to show and hide
            elements from the feed.
        </p>
    </div>
</template>
