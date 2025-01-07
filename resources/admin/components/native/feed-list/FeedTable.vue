<script>
import { onMounted, ref } from "vue";
import { columns } from "./columns.js";
import DataTable from "./DataTable.vue";
import { data } from "autoprefixer";

async function getData() {
    // Fetch data from your API here.
    return [
        {
            id: "728ed52f",
            title: "Ecommerce Subreddit",
            author: "John Doe",
            date: "2021-09-01",
            shortCode: "[wprb-subreddit-feed feed=21]",
            amount: 100,
            status: "pending",
            email: "m@example.com",
        },
    ];
}

export default {
    name: "FeedList",
    components: {
        DataTable,
    },
    setup() {
        const data = ref([]);
        onMounted(() => {
            getData().then((response) => {
                data.value = response;
            });
        });
        return {
            columns,
            data,
        };
    },
};
</script>

<template>
    <div>
        <DataTable :columns="columns" :data="data" />
    </div>
</template>
