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
                Yes, Delete
            </Button>
        </SheetFooter>
    </div>
</template>

<script setup>
import { ref } from "vue";
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

const confirmDelete = () => {
    // Logic to confirm deletion
    console.log("Content deleted");
};

const cancelDelete = () => {
    // Logic to cancel deletion
    console.log("Deletion cancelled");
};
</script>

<style scoped></style>
