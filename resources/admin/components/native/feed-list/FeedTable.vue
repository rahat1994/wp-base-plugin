<script setup>
import { onMounted, ref, reactive } from "vue";
import { columns } from "./columns.js";
import DataTable from "./DataTable.vue";
import { $get, $post } from "@/request";
import { Button } from "@/components/ui/button";
import {
    Pagination,
    PaginationEllipsis,
    PaginationFirst,
    PaginationLast,
    PaginationList,
    PaginationListItem,
    PaginationNext,
    PaginationPrev,
} from "@/components/ui/pagination";
const state = reactive({
    isLoading: false,
    error: null,
    data: [],
    users: [],
    total: 0,
    currentPage: 1,
});

async function getFeeds() {
    return new Promise(async (resolve) => {
        try {
            state.isLoading = true;
            state.error = null;
            const args = {
                route: "feeds",
                page: state.currentPage,
            };
            const { data, error: fetchError } = await $get(args);
            if (data && data.value) {
                if (!data.value.data.success) {
                    state.error = data.value.data.message;
                    return;
                }
                state.data = JSON.parse(data.value.data.feeds);
                state.total = JSON.parse(data.value.data.total);
                // console.log(JSON.parse(data.value.data.feeds));
                // console.log(JSON.parse(data.value.data.data));
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
}

async function getUsers() {
    return new Promise(async (resolve) => {
        try {
            state.isLoading = true;
            state.error = null;
            const args = {
                route: "users",
            };
            const { data, error: fetchError } = await $get(args);
            if (data && data.value) {
                if (!data.value.data.success) {
                    state.error = data.value.data.message;
                    return;
                }
                state.users = JSON.parse(data.value.data.users);
                console.log(state.users);
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
}

function updateCurrrentPage(page) {
    state.currentPage = page;
    getFeeds();
}
onMounted(() => {
    getFeeds();
    getUsers();
});
</script>

<template>
    <div>
        <DataTable
            :columns="columns"
            @reloadFeeds="getFeeds"
            :data="state.data"
            :totalNumberOfFeedItems="state.total"
            :users="state.users"
        />
        <div class="flex items-center justify-end py-4 space-x-2">
            <Pagination
                :page="state.currentPage"
                :total="state.total"
                :sibling-count="1"
                show-edges
                :default-page="1"
                @update:page="updateCurrrentPage"
            >
                <PaginationList
                    v-slot="{ items }"
                    class="flex items-center gap-1"
                >
                    <PaginationFirst />
                    <PaginationPrev />

                    <template v-for="(item, index) in items">
                        <PaginationListItem
                            v-if="item.type === 'page'"
                            :key="index"
                            :value="item.value"
                            as-child
                        >
                            <Button
                                class="w-10 h-10 p-0"
                                :variant="
                                    item.value === state.currentPage
                                        ? 'default'
                                        : 'outline'
                                "
                            >
                                {{ item.value }}
                            </Button>
                        </PaginationListItem>
                        <PaginationEllipsis
                            v-else
                            :key="item.type"
                            :index="index"
                        />
                    </template>

                    <PaginationNext />
                    <PaginationLast />
                </PaginationList>
            </Pagination>
        </div>
        <div v-if="state.isLoading">Loading...</div>
        <div v-if="state.error">{{ state.error }}</div>
    </div>
</template>
