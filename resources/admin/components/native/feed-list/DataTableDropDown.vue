<script setup>
import { Button } from "@/components/ui/button";
import { reactive } from "vue";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { MoreHorizontal } from "lucide-vue-next";
import swal from "sweetalert";
import { $post } from "@/request";
import LoadingSpinner from "../LoadingSpinner.vue";
// Props definition
const props = defineProps({
    feed: {
        type: Object,
    },
});
const state = reactive({
    isLoading: false,
    error: null,
    data: null,
});
const makeFeedStatusChangeRequest = function (feedId, status) {
    return new Promise(async (resolve) => {
        try {
            state.isLoading = true;

            state.error = null;
            const args = {
                route: "change-feed-status",
                id: feedId,
                status: status,
            };

            const { data, error: fetchError } = await $post(args);
            if (data && data.value) {
                if (!data.value.data.success) {
                    state.error = data.value.data.message;
                    return;
                }
                resolve(data.value);
            } else if (fetchError) {
                state.error = fetchError;
            }
        } catch (err) {
            state.error = err;
        } finally {
            state.isLoading = false;
            resolve();
        }
    });
};
// Methods
const changeFeedStatus = async function (feedId, status) {
    let data = await makeFeedStatusChangeRequest(feedId, status);
    console.log(data);
    if (data.success === true) {
        swal("Success", "Feed has been updated successfully", "success");
        const reloadButton = document.getElementById("reloadFeeds");
        if (reloadButton) {
            reloadButton.click();
        }
    }
};

const makeCacheRegenerationRequest = async function (feedId) {
    return new Promise(async (resolve) => {
        try {
            state.isLoading = true;

            state.error = null;
            const args = {
                route: "regenerate-cache",
                id: feedId,
            };
            const { data, error: fetchError } = await $post(args);
            if (data && data.value) {
                if (!data.value.data.success) {
                    state.error = data.value.data.message;
                    return;
                }
                resolve(data.value);
            } else if (fetchError) {
                state.error = fetchError;
            }
        } catch (err) {
            state.error = err;
        } finally {
            state.isLoading = false;
            resolve();
        }
    });
};

const regenrateCache = async function (feedId) {
    let data = await makeCacheRegenerationRequest(feedId);
    if (data.success === true) {
        swal("Success", "Cache regenrated successfully", "success");
        const reloadButton = document.getElementById("reloadFeeds");
        if (reloadButton) {
            reloadButton.click();
        }
    }
};
</script>

<template>
    <span>
        <LoadingSpinner v-if="state.isLoading" />
        <DropdownMenu v-if="!state.isLoading">
            <DropdownMenuTrigger as-child>
                <Button variant="ghost" class="w-8 h-8 p-0">
                    <span class="sr-only">Open menu</span>
                    <MoreHorizontal class="w-4 h-4" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
                <DropdownMenuLabel>Actions</DropdownMenuLabel>
                <DropdownMenuItem
                    v-if="feed.status === 'publish'"
                    @click="() => changeFeedStatus(props.feed.id, 'draft')"
                >
                    Unpublish
                </DropdownMenuItem>

                <DropdownMenuItem
                    v-if="
                        feed.status === 'draft' ||
                        feed.status === 'future' ||
                        feed.status === 'pending' ||
                        feed.status == 'trash'
                    "
                    @click="() => changeFeedStatus(feed.id, 'publish')"
                >
                    Publish
                </DropdownMenuItem>

                <DropdownMenuItem @click="() => regenrateCache(feed.id)">
                    Regenerate Cache
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    </span>
</template>
