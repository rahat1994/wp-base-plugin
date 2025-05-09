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
import { toTypedSchema } from "@vee-validate/zod";
import { reactive, onMounted } from "vue";
import * as z from "zod";
import { $get, $post } from "@/request";
import { useForm } from "vee-validate";
import LoadingSpinner from "@/components/native/LoadingSpinner.vue";
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

const formDefination = () => {
    return {
        validationSchema: secretsFormSchema,
        initialValues: {
            clientId: "",
            clientSecret: "",
        },
    };
};

const { values, resetForm, handleSubmit, isSubmitting, errors, defineField } =
    useForm(formDefination());

const state = reactive({
    isLoading: false,
    error: null,
});

const [clientIdField, clientIdFieldAttrs] = defineField("clientId");
const [clientSecretField, clientSecretFieldAttrs] = defineField("clientSecret");

const onSubmit = handleSubmit((values) => {
    return new Promise(async (resolve) => {
        try {
            state.isLoading = true;

            await new Promise((resolve) => setTimeout(resolve, 500));

            state.error = null;
            const args = {
                route: "settings",
                settings: JSON.stringify({
                    client_id: values.clientId,
                    client_secret: values.clientSecret,
                }),
            };
            const { data, error: fetchError } = await $post(args);
            if (data && data.value) {
                if (!data.value.data.success) {
                    state.error = data.value.data.message;
                    return;
                }
                state.data = data.value.data;
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
});

async function getSettings() {
    return new Promise(async (resolve) => {
        try {
            state.isLoading = true;

            await new Promise((resolve) => setTimeout(resolve, 500));
            const settingKeys = ["client_id", "client_secret"];

            state.error = null;
            const args = {
                route: "settings",
                settingKeys: settingKeys,
            };

            const { data, error: fetchError } = await $get(args);
            if (data && data.value) {
                if (!data.value.data.success) {
                    state.error = data.value.data.message;
                    return;
                }
                console.log(data.value.data.data);
                hydrateForm(JSON.parse(data.value.data.data));
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

const hydrateForm = (data) => {
    console.log(data.client_id);
    console.log("This is hydrate form");

    resetForm({
        values: {
            clientId: data.client_id,
            clientSecret: data.client_secret,
        },
    });
};
onMounted(async () => {
    getSettings();
});
</script>

<template>
    <div>
        <div>
            <h3 class="text-lg font-medium">Secrets</h3>
            <p class="text-sm text-muted-foreground">
                Update your client ID and client secret.
            </p>
            <p v-if="state.isLoading" class="text-sm text-muted-foreground">
                Loading ...
            </p>
        </div>
        <Separator />
        <form class="space-y-8" @submit="onSubmit">
            <FormField name="clientId">
                <FormItem>
                    <FormLabel>Client ID</FormLabel>
                    <FormControl>
                        <Input
                            type="text"
                            placeholder="Your client ID"
                            @disabled="state.isLoading"
                            v-bind="clientIdFieldAttrs"
                            v-model="clientIdField"
                        />
                    </FormControl>
                    <FormDescription>
                        This is the client ID for your application.
                    </FormDescription>
                    <FormMessage />
                </FormItem>
            </FormField>

            <FormField name="clientSecret">
                <FormItem>
                    <FormLabel>Client Secret</FormLabel>
                    <FormControl>
                        <Input
                            type="text"
                            placeholder="Your client secret"
                            @disabled="state.isLoading"
                            v-bind="clientSecretFieldAttrs"
                            v-model="clientSecretField"
                        />
                    </FormControl>
                    <FormDescription>
                        This is the client secret for your application.
                    </FormDescription>
                    <FormMessage />
                </FormItem>
            </FormField>

            <div class="flex justify-start">
                <Button
                    type="submit"
                    @disabled="isSubmitting || state.isLoading"
                >
                    <LoadingSpinner v-if="isSubmitting || state.isLoading" />
                    Update secrets
                </Button>
            </div>
        </form>
    </div>
</template>
