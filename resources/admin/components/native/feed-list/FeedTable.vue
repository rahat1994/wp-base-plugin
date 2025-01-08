<script setup>
import { onMounted, ref, reactive } from "vue";
import { columns } from "./columns.js";
import DataTable from "./DataTable.vue";
import { $get, $post } from "@/request";

const state = reactive({
    isLoading: false,
    error: null,
    data: [],
    users: [],
});

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

onMounted(() => {
    getFeeds();
    getUsers();
});
</script>

<template>
    <div>
        <DataTable :columns="columns" :data="state.data" />
        <div v-if="state.isLoading">Loading...</div>
        <div v-if="state.error">{{ state.error }}</div>
    </div>
</template>
