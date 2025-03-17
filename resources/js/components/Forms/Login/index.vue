<template>
    <form @submit="onSubmit" class="space-y-6" novalidate>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
                Email address
            </label>
            <div class="mt-1">
                <input
                    id="email"
                    type="email"
                    v-model="email"
                    autocomplete="email"
                    aria-describedby="email-error"
                    :class="[
                        'appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                        { 'border-red-500': errors.email },
                    ]"
                />
                <FieldError name="email" />
            </div>
        </div>

        <div>
            <label
                for="password"
                class="block text-sm font-medium text-gray-700"
            >
                Password
            </label>
            <div class="mt-1">
                <input
                    id="password"
                    type="password"
                    v-model="password"
                    autocomplete="current-password"
                    aria-describedby="password-error"
                    :class="[
                        'appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                        { 'border-red-500': errors.password },
                    ]"
                />
                <FieldError name="password" />
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input
                    id="remember-me"
                    type="checkbox"
                    v-model="remember"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                />
                <label
                    for="remember-me"
                    class="ml-2 block text-sm text-gray-900"
                >
                    Remember me
                </label>
            </div>

            <div class="text-sm">
                <router-link
                    :to="{ name: 'forgot-password' }"
                    class="font-medium text-indigo-600 hover:text-indigo-500"
                >
                    Forgot your password?
                </router-link>
            </div>
        </div>

        <div>
            <button
                type="submit"
                :disabled="isSubmitting"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                {{ isSubmitting ? "Signing in..." : "Sign in" }}
            </button>
        </div>
    </form>
</template>

<script setup>
import { useForm, useField } from "vee-validate";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { useErrorHandler } from "@/composables/useErrorHandler";
import FieldError from "@/components/Forms/FieldError.vue";
import schema from "./schema";

const router = useRouter();
const authStore = useAuthStore();
const { handleError } = useErrorHandler();

const { handleSubmit, errors, isSubmitting, setErrors } = useForm({
    validationSchema: schema,
    initialValues: {
        email: "",
        password: "",
        remember: false,
    },
});

const { value: email } = useField("email");
const { value: password } = useField("password");
const { value: remember } = useField("remember", undefined, {
    initialValue: false,
});

const onSubmit = handleSubmit((values) => {
    return authStore
        .login(values)
        .then(() => {
            router.push("/dashboard");
        })
        .catch((error) => {
            handleError(error, { setValidationErrors: setErrors });
        });
});
</script>
