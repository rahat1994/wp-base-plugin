<script setup>
import { ref, defineProps, reactive, defineEmits, onMounted } from "vue";
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/components/ui/accordion";
import { Switch } from "@/components/ui/switch";
import { Input } from "@/components/ui/input";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Button } from "@/components/ui/button";
import LoadingSpinner from "@/components/native/LoadingSpinner.vue";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import Separator from "@/components/ui/separator/Separator.vue";
import { $post } from "@/request";
const props = defineProps({
    config: {
        type: Object,
        required: true,
    },
    isLoading: {
        type: Boolean,
        required: false,
        default: false,
    },
    feed: {
        required: true,
    },
});
const emit = defineEmits(["update:config", "save"]);
const state = reactive({
    isLoading: props.isLoading,
    config: {
        title: {
            show: props.config.title.show,
            tag: props.config.title.tag,
            classes: props.config.title.classes,
        },
        description: {
            show: props.config.description.show,
            tag: props.config.description.tag,
            classes: props.config.description.classes,
        },
        list: {
            show: props.config.list.show,
            tag: props.config.list.tag,
            classes: props.config.list.classes,
        },
        links: {
            tag: props.config.links.tag,
            classes: props.config.links.classes,
        },
    },
});

function editorTitleStateUpdated(value, key) {
    state.config.title = {
        ...state.config.title,
        [key]: value,
    };
    emit("update:config", state.config);
}

function editorDescriptionStateUpdated(value, key) {
    state.config.description = {
        ...state.config.description,
        [key]: value,
    };
    emit("update:config", state.config);
}

function editorListStateUpdated(value, key) {
    state.config.list = {
        ...state.config.list,
        [key]: value,
    };
    emit("update:config", state.config);
}

function save() {
    return new Promise(async (resolve) => {
        try {
            state.isLoading = true;

            // await new Promise((resolve) => setTimeout(resolve, 500));

            state.error = null;
            const args = {
                route: "editor-save-feed-config",
                feed: props.feed,
                config: JSON.stringify(state.config),
            };
            const { data, error: fetchError } = await $post(args);
            if (data && data.value) {
                if (!data.value.data.success) {
                    state.error = data.value.data.message;
                    return;
                }
                state.isLoading = false;

                emit("save", state.config);
            } else if (fetchError) {
                state.error = fetchError;
            }
        } catch (err) {
            state.error = err;
        } finally {
            state.isLoading = false;
            resolve();
        }
    });
}

onMounted(() => {});
</script>

<template>
    <div class="hidden flex-col space-y-4 sm:flex md:order-2 p-4 shadow-md">
        <Button class="w-full" @click="save">
            <LoadingSpinner v-if="state.isLoading" />
            Save
        </Button>
        <Separator />
        <br />
        <Tabs default-value="account">
            <TabsList class="grid w-full grid-cols-2">
                <TabsTrigger value="account"> Feed Elements </TabsTrigger>
                <TabsTrigger value="password"> Styling </TabsTrigger>
            </TabsList>
            <TabsContent value="account">
                <Accordion type="single" default-value="item-1">
                    <AccordionItem value="item-1" open>
                        <AccordionTrigger>Title</AccordionTrigger>
                        <AccordionContent>
                            <div class="flex items-center space-x-2 mb-4 px-2">
                                <Switch
                                    id="airplane-mode"
                                    :checked="state.config.title.show"
                                    @update:checked="
                                        ($event) =>
                                            editorTitleStateUpdated(
                                                $event,
                                                'show'
                                            )
                                    "
                                />
                                <Label for="airplane-mode">Show Title</Label>
                            </div>
                            <div class="my-4 px-2 py-2">
                                <Select
                                    :default-value="state.config.title.tag"
                                    @update:modelValue="
                                        ($event) =>
                                            editorTitleStateUpdated(
                                                $event,
                                                'tag'
                                            )
                                    "
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="HTML Tag" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectLabel>Tags</SelectLabel>
                                            <SelectItem value="div"
                                                >div</SelectItem
                                            >
                                            <SelectItem value="span"
                                                >span</SelectItem
                                            >
                                            <SelectItem value="p">p</SelectItem>
                                            <SelectItem value="h1"
                                                >h1</SelectItem
                                            >
                                            <SelectItem value="h2"
                                                >h2</SelectItem
                                            >
                                            <SelectItem value="h3"
                                                >h3</SelectItem
                                            >
                                            <SelectItem value="h4"
                                                >h4</SelectItem
                                            >
                                            <SelectItem value="h5"
                                                >h5</SelectItem
                                            >
                                            <SelectItem value="h6"
                                                >h6</SelectItem
                                            >
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div class="my-4 px-2">
                                <Input
                                    type="text"
                                    class="w-full border border-gray-200 rounded-md p-2"
                                    placeholder="classes for the title separated by space"
                                    @update:modelValue="
                                        ($event) =>
                                            editorTitleStateUpdated(
                                                $event,
                                                'classes'
                                            )
                                    "
                                />
                            </div>
                        </AccordionContent>
                    </AccordionItem>
                </Accordion>
                <Accordion type="single" collapsible>
                    <AccordionItem value="item-2" open>
                        <AccordionTrigger>Description</AccordionTrigger>
                        <AccordionContent>
                            <div class="flex items-center space-x-2 my-4 px-2">
                                <Switch
                                    id="show-description"
                                    :checked="state.config.description.show"
                                    @update:checked="
                                        ($event) =>
                                            editorDescriptionStateUpdated(
                                                $event,
                                                'show'
                                            )
                                    "
                                />
                                <Label for="show-description"
                                    >Show Description</Label
                                >
                            </div>
                            <Label class="px-2"
                                >HTML tag for the description</Label
                            >
                            <div class="px-2 py-2">
                                <Select
                                    :default-value="
                                        state.config.description.tag
                                    "
                                    @update:modelValue="
                                        ($event) =>
                                            editorDescriptionStateUpdated(
                                                $event,
                                                'tag'
                                            )
                                    "
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="HTML Tag" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectLabel>Tags</SelectLabel>
                                            <SelectItem value="span"
                                                >span</SelectItem
                                            >
                                            <SelectItem value="p">p</SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <br />
                                <Input
                                    :default-value="
                                        state.config.description.classes
                                    "
                                    @update:modelValue="
                                        ($event) =>
                                            editorDescriptionStateUpdated(
                                                $event,
                                                'classes'
                                            )
                                    "
                                    type="text"
                                    class="w-full border border-gray-200 rounded-md p-2"
                                    placeholder="classes for description"
                                />
                            </div>
                        </AccordionContent>
                    </AccordionItem>
                </Accordion>
                <Accordion type="single" collapsible>
                    <AccordionItem value="item-3" open>
                        <AccordionTrigger>Post List</AccordionTrigger>
                        <AccordionContent>
                            <Label class="my-4 px-2">List type</Label>
                            <div class="my-4 px-2">
                                <Select
                                    class="mb-4"
                                    :default-value="state.config.list.tag"
                                    @update:modelValue="
                                        ($event) =>
                                            editorListStateUpdated(
                                                $event,
                                                'tag'
                                            )
                                    "
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="List type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectLabel>Type</SelectLabel>
                                            <SelectItem value="ul"
                                                >Unordered List</SelectItem
                                            >
                                            <SelectItem value="ol"
                                                >Ordered List</SelectItem
                                            >
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <br />
                                <Input
                                    :default-value="state.config.list.classes"
                                    @update:modelValue="
                                        ($event) =>
                                            editorDescriptionStateUpdated(
                                                $event,
                                                'classes'
                                            )
                                    "
                                    type="text"
                                    class="w-full border border-gray-200 rounded-md p-2"
                                    placeholder="classes for post links (separate with space)"
                                />
                            </div>
                        </AccordionContent>
                    </AccordionItem>
                </Accordion>
            </TabsContent>
            <TabsContent value="password">
                <p>password</p>
            </TabsContent>
        </Tabs>
    </div>
</template>
