<template>
    <div class="edit-sheet-content">
        <SheetHeader>
            <SheetTitle>Edit profile</SheetTitle>
            <SheetDescription>
                Make changes to your profile here. Click save when you're done.
            </SheetDescription>
        </SheetHeader>
        <div class="grid gap-4 py-4">
            <div class="grid grid-cols-4 items-center gap-4">
                <Label for="name" class="text-right"> Name </Label>
                <Input
                    id="name"
                    default-value="Pedro Duarte"
                    class="col-span-3"
                />
            </div>
            <div class="grid grid-cols-4 items-center gap-4">
                <Label for="username" class="text-right"> Username </Label>
                <Input
                    id="username"
                    default-value="@peduarte"
                    class="col-span-3"
                />
            </div>
        </div>
        <SheetFooter>
            <SheetClose as-child>
                <Button type="submit"> Save changes </Button>
            </SheetClose>
        </SheetFooter>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Input } from "@/components/ui/input";
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

const inputValue = ref("");

const saveContent = () => {
    console.log("Content saved:", inputValue.value);
};
</script>

<style scoped>
.edit-sheet-content {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
</style>
