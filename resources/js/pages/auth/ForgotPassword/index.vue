<template>
    <div
        class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8"
    >
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Forgot your password?
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Enter your email address and we'll send you a link to reset your
                password.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form @submit.prevent="handleSubmit" class="space-y-6">
                    <div>
                        <label
                            for="email"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Email address
                        </label>
                        <div class="mt-1">
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                autocomplete="email"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            />
                        </div>
                    </div>

                    <div
                        v-if="message"
                        :class="['text-sm text-center', messageClass]"
                    >
                        {{ message }}
                    </div>

                    <div>
                        <button
                            type="submit"
                            :disabled="loading"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                        >
                            <span v-if="loading">Processing...</span>
                            <span v-else>Send Reset Link</span>
                        </button>
                    </div>

                    <div class="text-sm text-center">
                        <router-link
                            :to="{ name: 'login' }"
                            class="font-medium text-indigo-600 hover:text-indigo-500"
                        >
                            Back to login
                        </router-link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";

const router = useRouter();
const authStore = useAuthStore();

const form = reactive({
    email: "",
});

const loading = ref(false);
const message = ref("");
const messageClass = ref("");

const handleSubmit = async () => {
    try {
        loading.value = true;
        message.value = "";

        // Assuming you'll add a forgotPassword action to your auth store
        await authStore.forgotPassword(form.email);

        message.value = "Password reset link has been sent to your email.";
        messageClass.value = "text-green-600";
        form.email = "";
    } catch (error) {
        message.value =
            error.response?.data?.message ||
            "Something went wrong. Please try again.";
        messageClass.value = "text-red-600";
    } finally {
        loading.value = false;
    }
};
</script>
