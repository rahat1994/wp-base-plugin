<script>
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import FeedTable from "@/components/native/feed-list/FeedTable.vue";
export default {
    name: "FeedList",
    components: {
        Tabs,
        TabsContent,
        TabsList,
        TabsTrigger,
        FeedTable,
    },
};
</script>

<style scoped>
/* Add your styles here */
</style>

<template>
    <div>
        <Tabs default-value="overview" class="space-y-4">
            <TabsList>
                <TabsTrigger value="overview"> Overview </TabsTrigger>
                <TabsTrigger value="analytics" disabled>
                    Analytics
                </TabsTrigger>
                <TabsTrigger value="reports" disabled> Reports </TabsTrigger>
                <TabsTrigger value="notifications" disabled>
                    Notifications
                </TabsTrigger>
            </TabsList>
            <TabsContent value="overview" class="space-y-4">
                <FeedTable />
            </TabsContent>
        </Tabs>
    </div>
</template>
