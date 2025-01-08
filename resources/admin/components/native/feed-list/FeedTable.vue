<script setup>
import { onMounted, ref, reactive } from "vue";
import { columns } from "./columns.js";
import DataTable from "./DataTable.vue";
import { $get, $post } from "@/request";

const state = reactive({
    isLoading: false,
    error: null,
    data: [],
});

async function getData() {
    // Fetch data from your API here.
    return [
        {
            id: "1",
            title: "Ecommerce Subreddit",
            author: "johndoe@gmail.com",
            date: "2021-09-01",
            shortCode: "[wprb-subreddit-feed feed=21]",
            amount: 100,
            status: "pending",
            email: "m@example.com",
        },
    ];
}

async function getFeeds() {
    return new Promise(async (resolve) => {
        try {
            state.isLoading = true;
            state.error = null;
            const args = {
                route: "feeds",
            };
            const { data, error: fetchError } = await $get(args);
            if (data && data.value) {
                if (!data.value.data.success) {
                    state.error = data.value.data.message;
                    return;
                }
                state.data = JSON.parse(data.value.data.feeds);
                console.log(state.data);
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

onMounted(() => {
    getData().then((response) => {
        state.data = response;
    });
    getFeeds();
});
</script>

<template>
    <div>
        <DataTable :columns="columns" :data="state.data" />
        <div v-if="state.isLoading">Loading...</div>
        <div v-if="state.error">{{ state.error }}</div>
    </div>
</template>
