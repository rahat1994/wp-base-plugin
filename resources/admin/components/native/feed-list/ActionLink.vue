<template>
    <Sheet>
        <SheetTrigger
            as-child
            :class="`text-sm font-thin cursor-pointer text-${color} ${
                hasSeparator ? 'border-l-2 border-gray-300 pl-1' : ''
            }`"
        >
            <a> {{ label }} </a>
        </SheetTrigger>
        <SheetContent class="pt-10">
            <EditSheetContent
                v-if="props.type === 'edit'"
                :data="props.data"
                @formSubmissionSuccess="handleFormSubmissionSuccess"
            />
            <ViewSheetContent
                v-else-if="props.type === 'view'"
                :data="props.data"
            />
            <DeleteSheetContent
                v-else-if="props.type === 'delete'"
                :data="props.data"
            />
        </SheetContent>
    </Sheet>
</template>

<script setup>
import DeleteSheetContent from "./sheet-content/DeleteSheetContent.vue";
import EditSheetContent from "./sheet-content/EditSheetContent.vue";
import ViewSheetContent from "./sheet-content/ViewSheetContent.vue";

import { Sheet, SheetContent, SheetTrigger } from "@/components/ui/sheet";
import { onMounted } from "vue";
const props = defineProps({
    to: {
        type: String,
        default: "#",
    },
    label: {
        type: String,
        default: "Edit",
    },
    color: {
        type: String,
        default: "blue-500",
        validator: (value) =>
            ["blue-500", "red-500", "green-500", "yellow-500"].includes(value),
    },
    hasSeparator: {
        type: Boolean,
        default: false,
    },
    type: {
        type: String,
        default: "edit",
        validator: (value) => ["edit", "view", "delete"].includes(value),
    },
    data: {
        type: Object,
        default: () => ({}),
    },
});

defineEmits(["formSubmissionSuccess"]);

const getComponentType = () => {
    switch (props.type) {
        case "edit":
            return EditSheetContent;
        case "view":
            return ViewSheetContent;
        case "delete":
            return DeleteSheetContent;
        default:
            return EditSheetContent;
    }
};

function handleFormSubmissionSuccess(data) {
    console.log("ActionLink handleFormSubmissionSuccess");
    emit("formSubmissionSuccess", data);
}

onMounted(() => {});
</script>

<style scoped>
/* Add any additional styles here */
</style>
