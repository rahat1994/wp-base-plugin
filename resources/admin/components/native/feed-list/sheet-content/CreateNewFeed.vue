<template>
    <div>
        <Sheet v-model:open="openSheet">
            <SheetTrigger>
                <Button class="bg-green-500 hover:bg-green-600" size="lg">
                    <Plus /> New Feed
                </Button>
            </SheetTrigger>
            <SheetContent class="pt-10">
                <SheetHeader class="mb-4">
                    <SheetTitle class="text-lg font-semibold"
                        >New Feed</SheetTitle
                    >
                    <SheetDescription class="text-sm text-gray-600">
                        Create a new feed to display on your website.
                    </SheetDescription>
                </SheetHeader>
                <FeedForm
                    :users="props.users"
                    @formSubmissionSuccess="feedFormSubmitted"
                />
                <SheetFooter class="flex justify-end space-x-2"> </SheetFooter>
            </SheetContent>
        </Sheet>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Button } from "@/components/ui/button";
import { Plus } from "lucide-vue-next";
import FeedForm from "@/components/native/feed-list/sheet-content/FeedForm.vue";

import {
    Sheet,
    SheetClose,
    SheetContent,
    SheetDescription,
    SheetFooter,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from "@/components/ui/sheet";

const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
});
const emit = defineEmits(["reloadFeeds"]);
const openSheet = ref(false);

const confirmDelete = () => {
    // Logic to confirm deletion
    console.log("Content deleted");
};

const cancelDelete = () => {
    // Logic to cancel deletion
    console.log("Deletion cancelled");
};

onMounted(() => {
    console.log("Sheet content mounted");
});

const onSheetOpen = () => {
    console.log("Sheet opened");
};

const feedFormSubmitted = (data) => {
    openSheet.value = false;
    emit("reloadFeeds");
};
</script>

<style scoped></style>
