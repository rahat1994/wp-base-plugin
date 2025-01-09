<script setup>
import { Button } from "@/components/ui/button";
import { Separator } from "@/components/ui/separator";
import { Progress } from "@/components/ui/progress";
import { useForm } from "vee-validate";
import LoadingSpinner from "../../loadingSpinner.vue";
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
        author_email: z
            .string({
                required_error: "Author email is required.",
            })
            .email({
                message: "Author email must be a valid email.",
            }),
        subreddit_url: z
            .string({
                required_error: "Subreddit URL is required.",
            })
            .regex(/^https:\/\/(www\.)?reddit\.com\/r\/[A-Za-z0-9_]+\/?$/, {
                message: "Subreddit URL must be a valid URL to a subreddit.",
            }),
    })
);

const { handleSubmit } = useForm({
    validationSchema: secretsFormSchema,
});

const onSubmit = handleSubmit((values) => {
    console.log(JSON.stringify(values, null, 2));
    createNewFeed(values);
});

async function createNewFeed(values) {
    // await for 3 secs

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
            <FormField v-slot="{ componentField }" name="title">
                <FormItem>
                    <FormLabel>Feed title</FormLabel>
                    <FormControl>
                        <Input
                            type="text"
                            placeholder="Ecommerce subreddit feed"
                            v-bind="componentField"
                        />
                    </FormControl>
                    <FormDescription>
                        This is your public display name.
                    </FormDescription>
                    <FormMessage />
                </FormItem>
            </FormField>
            <FormField
                v-if="props.users.length > 0"
                v-slot="{ componentField }"
                name="author_email"
            >
                <FormItem>
                    <FormLabel>Email</FormLabel>

                    <Select v-bind="componentField">
                        <FormControl>
                            <SelectTrigger>
                                <SelectValue
                                    placeholder="Select a verified email to display"
                                />
                            </SelectTrigger>
                        </FormControl>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem
                                    v-for="user in props.users"
                                    :key="user.email"
                                    :value="user.email"
                                >
                                    {{ user.email }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <FormDescription>
                        You can manage email addresses in your
                        <a href="/examples/forms">email settings</a>.
                    </FormDescription>
                    <FormMessage />
                </FormItem>
            </FormField>

            <FormField v-slot="{ componentField }" name="subreddit_url">
                <FormItem>
                    <FormLabel>Subreddit URL</FormLabel>
                    <FormControl>
                        <Input
                            type="text"
                            placeholder="Your subreddit URL"
                            v-bind="componentField"
                            v-model="props.feedData.subreddit_url"
                        />
                    </FormControl>
                    <FormDescription>
                        This is the subreddit URL for your post.
                    </FormDescription>
                    <FormMessage />
                </FormItem>
            </FormField>
            <div class="flex justify-start pt-2">
                <Button type="submit" :disabled="state.isLoading">
                    <LoadingSpinner v-if="state.isLoading" /> Save
                </Button>
            </div>
        </form>
    </div>
</template>
