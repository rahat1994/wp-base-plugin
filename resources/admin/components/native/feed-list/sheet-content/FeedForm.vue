<script setup>
import { Button } from "@/components/ui/button";
import {
    Form,
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
import { Separator } from "@/components/ui/separator";

import { toast } from "@/components/ui/toast";
import { toTypedSchema } from "@vee-validate/zod";
import { h, ref } from "vue";
import * as z from "zod";

const secretsFormSchema = toTypedSchema(
    z.object({
        title: z
            .string({
                required_error: "Title is required.",
            })
            .min(2, {
                message: "Title must be at least 2 characters.",
            }),
        author: z.string({
            required_error: "Author is required.",
        }),
        subreddit_url: z
            .string({
                required_error: "Subreddit URL is required.",
            })
            .url({
                message: "Subreddit URL must be a valid URL.",
            }),
    })
);

const authors = ref([
    { value: "author1", label: "Author 1" },
    { value: "author2", label: "Author 2" },
    { value: "author3", label: "Author 3" },
]);

async function onSubmit(values) {
    toast({
        title: "You submitted the following values:",
        description: h(
            "pre",
            { class: "mt-2 w-[340px] rounded-md bg-slate-950 p-4" },
            h("code", { class: "text-white" }, JSON.stringify(values, null, 2))
        ),
    });
}
</script>

<template>
    <Separator />
    <Form
        v-slot="{ setFieldValue }"
        :validation-schema="secretsFormSchema"
        class="space-y-8"
        @submit="onSubmit"
    >
        <FormField v-slot="{ componentField }" name="title">
            <FormItem>
                <FormLabel>Title</FormLabel>
                <FormControl>
                    <Input
                        type="text"
                        placeholder="Your title"
                        v-bind="componentField"
                    />
                </FormControl>
                <FormDescription>
                    This is the title for your post.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <FormField v-slot="{ componentField }" name="email">
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
                            <SelectItem value="m@example.com">
                                m@example.com
                            </SelectItem>
                            <SelectItem value="m@google.com">
                                m@google.com
                            </SelectItem>
                            <SelectItem value="m@support.com">
                                m@support.com
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
                    />
                </FormControl>
                <FormDescription>
                    This is the subreddit URL for your post.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <div class="flex justify-start">
            <Button type="submit"> Update secrets </Button>
        </div>
    </Form>
</template>
