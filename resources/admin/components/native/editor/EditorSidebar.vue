<script setup>
import { ref } from 'vue';
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/components/ui/accordion";
import { Switch } from "@/components/ui/switch";
import { Input } from '@/components/ui/input'
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
</script>

<template>
                <div
                    class="hidden flex-col space-y-4 py-2 sm:flex md:order-2 bg-blue-200 p-4 shadow-md"
                >
                    <Accordion type="single" default-value="item-1">
                        <AccordionItem value="item-1" open>
                            <AccordionTrigger>Title</AccordionTrigger>
                            <AccordionContent>
                                <div class="flex items-center space-x-2 mb-4">
                                    <Switch id="airplane-mode" />
                                    <Label for="airplane-mode"
                                        >Show Title</Label
                                    >
                                </div>
                                <div class="mb-4">
                                    <Select>
                                        <SelectTrigger>
                                            <SelectValue
                                                placeholder="HTML Tag"
                                            />
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
                                                <SelectItem value="p"
                                                    >p</SelectItem
                                                >
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
                                <div class="mb-4">
                                    <Input
                                        type="text"
                                        class="w-full border border-gray-200 rounded-md p-2"
                                        placeholder="classes for description"
                                    />
                                </div>
                            </AccordionContent>
                        </AccordionItem>
                    </Accordion>
                    <Accordion type="single" collapsible>
                        <AccordionItem value="item-2" open>
                            <AccordionTrigger>Description</AccordionTrigger>
                            <AccordionContent>
                                <div class="flex items-center space-x-2 mb-4">
                                    <Switch id="airplane-mode" />
                                    <Label for="airplane-mode"
                                        >Show Title</Label
                                    >
                                </div>
                                <Label>HTML tag for the title</Label>
                                <Select>
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
                                <br />
                                <Input
                                    type="text"
                                    class="w-full border border-gray-200 rounded-md p-2"
                                    placeholder="classes for description"
                                />
                            </AccordionContent>
                        </AccordionItem>
                    </Accordion>
                    <Accordion type="single" collapsible>
                        <AccordionItem value="item-2" open>
                            <AccordionTrigger>Post Links</AccordionTrigger>
                            <AccordionContent>
                                <Label>HTML tag for the links</Label>
                                <Select>
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
                                <br />
                                <Select>
                                    <SelectTrigger>
                                        <SelectValue placeholder="List type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectLabel>Type</SelectLabel>
                                            <SelectItem value="unordered_list"
                                                >Unordered List</SelectItem
                                            >
                                            <SelectItem value="ordered_list"
                                                >Ordered List</SelectItem
                                            >
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <br />
                                <Input
                                    type="text"
                                    class="w-full border border-gray-200 rounded-md p-2"
                                    placeholder="classes for post links (separate with space)"
                                />
                            </AccordionContent>
                        </AccordionItem>
                    </Accordion>
                </div>
</template>
