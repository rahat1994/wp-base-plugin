<template>
    <div class="sheet-content">
        <SheetHeader>
            <SheetTitle>Edit profile</SheetTitle>
            <SheetDescription>
                Make changes to your profile here. Click save when you're done.
            </SheetDescription>
        </SheetHeader>
        <div class="grid gap-4 py-4">
            <h2>{{ data.title }}</h2>
            <p><strong>Author:</strong> {{ data.author }}</p>
            <p><strong>Status:</strong> {{ data.status }}</p>
            <p><strong>Shortcode:</strong> {{ data.shortcode }}</p>
        </div>
        <SheetFooter>
            <SheetClose as-child>
                <Button type="submit"> Close </Button>
            </SheetClose>
        </SheetFooter>
    </div>
</template>

<script>
import { data } from "autoprefixer";
import { defineComponent, onMounted, toRefs } from "vue";

export default defineComponent({
    name: "ViewSheetContent",
    props: {
        data: {
            type: Object,
            required: true,
            default: () => ({
                title: "",
                author: "",
                status: "",
                shortcode: "",
            }),
        },
    },
    setup(props) {
        const { data } = toRefs(props);
        return { data };
    },
});

onMounted(() => {
    console.log("Sheet content mounted");
    console.log(data.value);
});
</script>

<style scoped>
.sheet-content {
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
</style>
