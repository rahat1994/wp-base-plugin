import { createMemoryHistory, createRouter } from "vue-router";

import FeedList from "@/views/FeedList.vue";
import Settings from "@/views/Settings.vue";
import Editor from "@/views/Editor.vue";

const routes = [
    { path: "/", component: FeedList },
    { path: "/settings", component: Settings },
    { path: "/editor/:id", component: Editor },
];

const router = createRouter({
    history: createMemoryHistory(),
    routes,
});

export default router;
