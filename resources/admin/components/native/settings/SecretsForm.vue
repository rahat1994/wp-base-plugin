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
import { Input } from "@/components/ui/input";
import { Separator } from "@/components/ui/separator";

import { toast } from "@/components/ui/toast";
import { cn } from "@/lib/utils";
import { toTypedSchema } from "@vee-validate/zod";
import { h, ref } from "vue";
import * as z from "zod";

const secretsFormSchema = toTypedSchema(
    z.object({
        clientId: z
            .string({
                required_error: "Client ID is required.",
            })
            .min(2, {
                message: "Client ID must be at least 2 characters.",
            }),
        clientSecret: z
            .string({
                required_error: "Client Secret is required.",
            })
            .min(2, {
                message: "Client Secret must be at least 2 characters.",
            }),
    })
);

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
    <div>
        <h3 class="text-lg font-medium">Secrets</h3>
        <p class="text-sm text-muted-foreground">
            Update your client ID and client secret.
        </p>
    </div>
    <Separator />
    <Form
        v-slot="{ setFieldValue }"
        :validation-schema="secretsFormSchema"
        class="space-y-8"
        @submit="onSubmit"
    >
        <FormField v-slot="{ componentField }" name="clientId">
            <FormItem>
                <FormLabel>Client ID</FormLabel>
                <FormControl>
                    <Input
                        type="text"
                        placeholder="Your client ID"
                        v-bind="componentField"
                    />
                </FormControl>
                <FormDescription>
                    This is the client ID for your application.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <FormField v-slot="{ componentField }" name="clientSecret">
            <FormItem>
                <FormLabel>Client Secret</FormLabel>
                <FormControl>
                    <Input
                        type="text"
                        placeholder="Your client secret"
                        v-bind="componentField"
                    />
                </FormControl>
                <FormDescription>
                    This is the client secret for your application.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <div class="flex justify-start">
            <Button type="submit"> Update secrets </Button>
        </div>
    </Form>
</template>
