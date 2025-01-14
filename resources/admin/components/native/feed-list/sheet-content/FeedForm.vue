<script setup>
import { Button } from "@/components/ui/button";
import { Separator } from "@/components/ui/separator";
import { useForm } from "vee-validate";
import LoadingSpinner from "../../LoadingSpinner.vue";
import { Checkbox } from "@/components/ui/checkbox";

import {
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Input } from "@/components/ui/input";
import { $post } from "@/request";

import { useToast } from "@/components/ui/toast/use-toast";
const { toast } = useToast();

import { toTypedSchema } from "@vee-validate/zod";
import { h, onMounted, ref, watchEffect, reactive } from "vue";
import * as z from "zod";

const props = defineProps({
    context: {
        type: String,
        default: "create",
    },
    feedData: {
        type: Object,
        required: false,
        default: () => ({
            title: "",
            author_email: "",
            subreddit_url: "",
        }),
    },
    users: {
        type: Array,
        required: true,
        default: () => [],
    },
});
const emit = defineEmits(["formSubmissionSuccess"]);

const state = reactive({
    isLoading: false,
    error: null,
    data: [],
    users: [],
});

const secretsFormSchema = toTypedSchema(
    z.object({
        title: z
            .string({
                required_error: "Title is required.",
            })
            .min(2, {
                message: "Title must be at least 2 characters.",
            }),
        feed_type: z.string({
            required_error: "Feed type is required.",
        }),
        subreddit_url: z
            .string({
                required_error: "Subreddit URL is required.",
            })
            .regex(/^https:\/\/(www\.)?reddit\.com\/r\/[A-Za-z0-9_]+\/?$/, {
                message: "Subreddit URL must be a valid URL to a subreddit.",
            }),
        should_be_cached: z.boolean().default(false).optional(),
    })
);

const { handleSubmit, defineField } = useForm({
    validationSchema: secretsFormSchema,
    initialValues: {
        title: "",
        feed_type: "new",
        subreddit_url: "",
        should_be_cached: false,
    },
});

const [titleField, titleFieldAttrs] = defineField("title");
const [feedTypeField, feedTypeFieldAttrs] = defineField("feed_type");
const [subredditUrlField, subredditUrlFieldAttrs] =
    defineField("subreddit_url");
const [shouldBeCachedField, shouldBeCachedFieldAttrs] =
    defineField("should_be_cached");

const onSubmit = handleSubmit((values) => {
    createNewFeed(values);
});

async function createNewFeed(values) {
    return new Promise(async (resolve) => {
        try {
            state.isLoading = true;

            await new Promise((resolve) => setTimeout(resolve, 500));

            state.error = null;
            const args = {
                route: "feeds",
                ...values,
            };
            const { data, error: fetchError } = await $post(args);
            if (data && data.value) {
                if (!data.value.data.success) {
                    state.error = data.value.data.message;
                    return;
                }
                emit("formSubmissionSuccess", data.value.data);
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
</script>

<template>
    <div>
        <Separator />
        <br />
        <form class="pt-2" @submit="onSubmit">
            <FormField name="title">
                <FormItem>
                    <FormLabel>Feed title</FormLabel>
                    <FormControl>
                        <Input
                            type="text"
                            placeholder="Ecommerce subreddit feed"
                            v-bind="titleFieldAttrs"
                            v-model="titleField"
                        />
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>
            <br />
            <FormField name="feed_type">
                <FormItem>
                    <FormLabel>Feed Type</FormLabel>

                    <Select v-model="feedTypeField" v-bind="feedTypeFieldAttrs">
                        <FormControl>
                            <SelectTrigger>
                                <SelectValue
                                    placeholder="Select the feed order"
                                />
                            </SelectTrigger>
                        </FormControl>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem key="new" value="new"
                                    >New</SelectItem
                                >
                                <SelectItem key="popular" value="popular"
                                    >Popular</SelectItem
                                >
                                <SelectItem key="gold" value="gold"
                                    >Gold</SelectItem
                                >
                                <SelectItem key="default" value="default"
                                    >Default</SelectItem
                                >
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <FormMessage />
                </FormItem>
            </FormField>
            <br />
            <FormField name="subreddit_url">
                <FormItem>
                    <FormLabel>Subreddit URL</FormLabel>
                    <FormControl>
                        <Input
                            type="text"
                            placeholder="Your subreddit URL"
                            v-bind="subredditUrlFieldAttrs"
                            v-model="subredditUrlField"
                        />
                    </FormControl>
                    <FormDescription>
                        This is the subreddit URL for your post.
                    </FormDescription>
                    <FormMessage />
                </FormItem>
            </FormField>
            <br />
            <FormField name="should_be_cached" v-slot="{ value, handleChange }">
                <FormItem>
                    <FormControl>
                        <div class="flex items-center space-x-2 mt-4">
                            <Checkbox
                                id="should_be_cached"
                                :checked="value"
                                @update:checked="handleChange"
                            />
                            <div class="grid gap-1.5 leading-none">
                                <label
                                    for="terms1"
                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                >
                                    Cache this feed
                                </label>
                                <p class="text-sm text-muted-foreground">
                                    Cache this feed for faster loading times.
                                    And reduce API calls to reddit.
                                </p>
                            </div>
                        </div>
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>
            <br />
            <div class="flex justify-start">
                <Button type="submit" :disabled="state.isLoading">
                    <LoadingSpinner v-if="state.isLoading" /> Save
                </Button>
            </div>
        </form>
    </div>
</template>
