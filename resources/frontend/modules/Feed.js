import { $get } from "../request";

export default function Feed() {
    return {
        feedId: null,
        isLoading: false,
        data: [],
        init() {
            this.$nextTick(async () => {
                console.log(this.feedId);
                let data = await this.getUsers(this.feedId);
                console.log(data);
            });
        },
        getUsers: async (feedId) => {
            return new Promise(async (resolve) => {
                try {
                    this.isLoading = true;
                    this.error = null;
                    const args = {
                        route: "get-feed-posts",
                        feed_id: feedId,
                    };
                    console.log("Hello");
                    const { data, error: fetchError } = await $get(args);
                    console.log("Hello 2");
                    if (data && data.value) {
                        if (!data.value.data.success) {
                            this.error = data.value.data.message;
                            return;
                        }
                        console.log(data);
                    } else if (fetchError) {
                        this.error = fetchError;
                    }
                } catch (err) {
                    this.error = err;
                } finally {
                    this.isLoading = false;
                    resolve();
                }
            });
        },

        renderFeed() {
            return "<ul><li x-text='feedId'></li><ul>";
        },
    };
}
