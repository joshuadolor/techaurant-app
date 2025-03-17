<template>
    <form @submit="onSubmit" class="space-y-6" novalidate>
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">
                Name
            </label>
            <div class="mt-1">
                <input
                    id="name"
                    type="text"
                    v-model="name"
                    required
                    :class="[
                        'appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                        { 'border-red-500': errors.name },
                    ]"
                />
                <FieldError name="name" />
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
                Email address
            </label>
            <div class="mt-1">
                <input
                    id="email"
                    type="email"
                    v-model="email"
                    required
                    autocomplete="email"
                    :class="[
                        'appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                        { 'border-red-500': errors.email },
                    ]"
                />
            </div>
            <FieldError name="email" />
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
                    required
                    autocomplete="new-password"
                    :class="[
                        'appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                        { 'border-red-500': errors.password },
                    ]"
                />
            </div>
            <FieldError name="password" />
        </div>

        <div>
            <label
                for="passwordConfirmation"
                class="block text-sm font-medium text-gray-700"
            >
                Confirm Password
            </label>
            <div class="mt-1">
                <input
                    id="passwordConfirmation"
                    type="password"
                    autocomplete="new-password"
                    v-model="passwordConfirmation"
                    required
                    :class="[
                        'appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                        { 'border-red-500': errors.passwordConfirmation },
                    ]"
                />
            </div>
            <FieldError name="passwordConfirmation" />
        </div>

        <div>
            <button
                type="submit"
                :disabled="isSubmitting"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                {{ isSubmitting ? "Registering..." : "Register" }}
            </button>
        </div>
    </form>
</template>

<script setup>
import { useForm, useField, useFieldArray } from "vee-validate";
import { useErrorHandler } from "@/composables/useErrorHandler";
import FieldError from "@/components/Forms/FieldError.vue";
import schema from "./schema";
import AccountService from "@/services/account";

const { handleError } = useErrorHandler();

const { handleSubmit, errors, isSubmitting, setErrors } = useForm({
    validationSchema: schema,
    initialValues: {
        name: "",
        email: "",
        password: "",
        passwordConfirmation: "",
    },
});

const { value: name } = useField("name");
const { value: email } = useField("email");
const { value: password } = useField("password");
const { value: passwordConfirmation } = useField("passwordConfirmation");
const onSubmit = handleSubmit(async (values) => {
    try {
        const response = await AccountService.register(values);
        if (response.data.token) {
            localStorage.setItem("token", response.data.token);
            window.location.href = "/dashboard";
        }
    } catch (error) {
        handleError(error, { setValidationErrors: setErrors });
    }
});
</script>
