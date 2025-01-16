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
import { reactive, onMounted, ref } from "vue";
import * as z from "zod";
import { $get, $post } from "@/request";
import { useForm } from "vee-validate";
import LoadingSpinner from "@/components/native/LoadingSpinner.vue";
const secretsFormSchema = toTypedSchema(
    z.object({
        clientId: z.string({
            required_error: "Client ID is required.",
        }),
        clientSecret: z.string({
            required_error: "Client Secret is required.",
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

const {
    values,
    resetForm,
    handleSubmit,
    isSubmitting,
    meta,
    errors,
    defineField,
} = useForm(formDefination());

const state = reactive({
    isLoading: false,
    error: null,
});

const showPassword = ref(false);

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

const toggleShowPassword = () => {
    showPassword.value = !showPassword.value;
};
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
                        <div class="relative">
                            <Input
                                :type="showPassword ? 'text' : 'password'"
                                placeholder="Your client secret"
                                @disabled="state.isLoading"
                                v-bind="clientSecretFieldAttrs"
                                v-model="clientSecretField"
                            />
                            <button
                                type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"
                                @click="toggleShowPassword"
                            >
                                <span v-if="showPassword">Hide</span>
                                <span v-else>Show</span>
                            </button>
                        </div>
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
                    :disabled="!meta.dirty || state.isLoading"
                >
                    <LoadingSpinner v-if="isSubmitting || state.isLoading" />
                    Update secrets
                </Button>
            </div>
        </form>
    </div>
</template>
