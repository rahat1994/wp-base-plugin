<script setup>
import { ref, defineProps, defineEmits, reactive } from "vue";
import { Button } from "@/components/ui/button";
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
import { $post } from "@/request";
import LoadingSpinner from "../../LoadingSpinner.vue";
const props = defineProps({
    data: {
        type: Object,
        required: false,
    },
});

const state = reactive({
    isLoading: false,
    error: null,
});
const emit = defineEmits(["feedDeleted"]);

const confirmDelete = () => {
    // Logic to confirm deletion
    console.log("Deletion confirmed");
    deleteFeed();
};

const cancelDelete = () => {
    // Logic to cancel deletion
    console.log("Deletion cancelled");
};

async function deleteFeed() {
    return new Promise(async (resolve) => {
        try {
            state.isLoading = true;
            state.error = null;
            const args = {
                route: "delete-feed",
                id: props.data.id,
            };
            console.log("deleteFeed");
            const { data, error: fetchError } = await $post(args);
            if (data && data.value) {
                if (!data.value.data.success) {
                    state.error = data.value.data.message;
                    return;
                }
                emit("feedDeleted");
            } else if (fetchError) {
                state.error = fetchError;
            }
        } catch (err) {
            state.error = err;
            console.log(err);
        } finally {
            state.isLoading = false;
            resolve();
        }
    });
}
</script>
<template>
    <div>
        <SheetHeader class="mb-4">
            <SheetTitle class="text-lg font-semibold">Are you sure?</SheetTitle>
            <SheetDescription class="text-sm text-gray-600">
                This action can't be undone. Are you sure you want to delete
                this content?
            </SheetDescription>
        </SheetHeader>

        <SheetFooter class="flex justify-end space-x-2">
            <SheetClose as-child>
                <Button variant="outline" class="px-4 py-2">Cancel</Button>
            </SheetClose>
            <Button
                variant="destructive"
                @click="confirmDelete"
                class="px-4 py-2"
            >
                <LoadingSpinner v-if="state.isLoading" />
                Yes, Delete
            </Button>
        </SheetFooter>
    </div>
</template>

<style scoped></style>
