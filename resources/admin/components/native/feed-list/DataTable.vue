<script setup lang="ts" generic="TData, TValue">
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { Plus, RefreshCw, Users } from "lucide-vue-next";
import CreateNewFeed from "./sheet-content/CreateNewFeed.vue";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";

import {
    FlexRender,
    getPaginationRowModel,
    getCoreRowModel,
    useVueTable,
} from "@tanstack/vue-table";

const props = defineProps<{
    columns: [];
    data: [];
    users: [];
    totalNumberOfFeedItems: number;
}>();

// defineEmits(["reloadFeeds", "formSubmissionSuccess"]);

const table = useVueTable({
    get data() {
        return props.data;
    },
    get columns() {
        return props.columns;
    },
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
});
const emit = defineEmits(["reloadFeeds"]);
const reloadFeeds = () => {
    console.log("Reloading feeds...");
    emit("reloadFeeds");
};

function handleFormSubmissionSuccess(data) {
    console.log("DataTable handleFormSubmissionSuccess");
    emit("reloadFeeds");
}
</script>

<template>
    <div class="w-full">
        <div class="flex gap-2 items-center py-4">
            <Input
                placeholder="Filter title..."
                :model-value="table.getColumn('title')?.getFilterValue() as string"
                @update:model-value="
                    table.getColumn('title')?.setFilterValue($event)
                "
            />
            <CreateNewFeed :users="props.users" @reloadFeeds="reloadFeeds" />
            <Button
                @click="reloadFeeds"
                class="bg-blue-500 hover:bg-blue-600"
                size="lg"
                id="reloadFeeds"
            >
                <RefreshCw
            /></Button>
        </div>
        <div class="border rounded-md">
            <Table>
                <TableHeader>
                    <TableRow
                        v-for="headerGroup in table.getHeaderGroups()"
                        :key="headerGroup.id"
                    >
                        <TableHead
                            v-for="header in headerGroup.headers"
                            :key="header.id"
                        >
                            <FlexRender
                                v-if="!header.isPlaceholder"
                                :render="header.column.columnDef.header"
                                :props="header.getContext()"
                            />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="table.getRowModel().rows?.length">
                        <TableRow
                            v-for="row in table.getRowModel().rows"
                            :key="row.id"
                            :data-state="
                                row.getIsSelected() ? 'selected' : undefined
                            "
                        >
                            <TableCell
                                v-for="cell in row.getVisibleCells()"
                                :key="cell.id"
                                @formSubmissionSuccess="
                                    handleFormSubmissionSuccess
                                "
                            >
                                <FlexRender
                                    @formSubmissionSuccess="
                                        handleFormSubmissionSuccess
                                    "
                                    :render="cell.column.columnDef.cell"
                                    :props="cell.getContext()"
                                />
                            </TableCell>
                        </TableRow>
                    </template>
                    <template v-else>
                        <TableRow>
                            <TableCell
                                :colspan="columns.length"
                                class="h-24 text-center"
                            >
                                No results.
                            </TableCell>
                        </TableRow>
                    </template>
                </TableBody>
            </Table>
        </div>
    </div>
</template>
