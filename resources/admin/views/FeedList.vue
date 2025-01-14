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
        <Tabs default-value="feeds" class="space-y-4">
            <TabsList>
                <TabsTrigger value="feeds"> Overview </TabsTrigger>
            </TabsList>
            <TabsContent value="feeds" class="space-y-4">
                <FeedTable />
            </TabsContent>
        </Tabs>
    </div>
</template>
