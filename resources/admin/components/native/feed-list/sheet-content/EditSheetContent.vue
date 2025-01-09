<template>
    <div class="edit-sheet-content">
        <SheetHeader>
            <SheetTitle>Edit Feed</SheetTitle>
            <SheetDescription> Edit the feed details below. </SheetDescription>
        </SheetHeader>
        <div class="grid gap-4 py-4">
            <EditFeedForm
                :feedData="props.data"
                @formSubmissionSuccess="handleFormSubmissionSuccess"
            />
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { Button } from "@/components/ui/button";
import EditFeedForm from "@/components/native/feed-list/sheet-content/EditFeedForm.vue";
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
import { data } from "autoprefixer";

const props = defineProps({
    data: {
        type: Object,
        required: false,
    },
});
const emit = defineEmits(["formSubmissionSuccess"]);

const inputValue = ref("");
const feedData = ref({
    title: "Feed Title",
});

const saveContent = () => {
    console.log("Content saved:", inputValue.value);
};

function handleFormSubmissionSuccess(data) {
    console.log("EditSheetContent handleFormSubmissionSuccess");

    emit("formSubmissionSuccess");
}

onMounted(() => {
    console.log("EditSheetContent mounted");
    console.log(props.data);
});
</script>

<style scoped>
.edit-sheet-content {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
</style>
