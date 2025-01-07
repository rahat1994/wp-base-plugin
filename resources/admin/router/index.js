import { createMemoryHistory, createRouter } from "vue-router";

import FeedList from "@/views/FeedList.vue";
import Settings from "@/views/Settings.vue";

const routes = [
    { path: "/", component: FeedList },
    { path: "/settings", component: Settings },
];

const router = createRouter({
    history: createMemoryHistory(),
    routes,
});

export default router;
