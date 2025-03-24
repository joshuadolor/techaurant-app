<template>
    <div
        class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8"
    >
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2
                    class="mt-6 text-center text-3xl font-extrabold text-gray-900"
                >
                    Verify Your Email
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    We've sent a verification link to your email address. Please
                    check your inbox and click the link to verify your account.
                </p>
            </div>

            <div v-if="error" class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <XCircleIcon
                            class="h-5 w-5 text-red-400"
                            aria-hidden="true"
                        />
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            {{ error }}
                        </h3>
                    </div>
                </div>
            </div>

            <div v-if="success" class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <CheckCircleIcon
                            class="h-5 w-5 text-green-400"
                            aria-hidden="true"
                        />
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">
                            {{ success }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="mt-8 space-y-6">
                <div>
                    <button
                        @click="resendVerification"
                        :disabled="isResending"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                    >
                        <span
                            class="absolute left-0 inset-y-0 flex items-center pl-3"
                        >
                            <RefreshIcon
                                v-if="!isResending"
                                class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400"
                                aria-hidden="true"
                            />
                            <svg
                                v-else
                                class="animate-spin h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                        </span>
                        {{
                            isResending
                                ? "Sending..."
                                : "Resend Verification Email"
                        }}
                    </button>
                </div>

                <div class="text-sm text-center">
                    <router-link
                        to="/login"
                        class="font-medium text-indigo-600 hover:text-indigo-500"
                    >
                        Back to Login
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import {
    XCircleIcon,
    CheckCircleIcon,
    RefreshIcon,
} from "@heroicons/vue/solid";

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

const error = ref("");
const success = ref("");
const isResending = ref(false);

const resendVerification = async () => {
    try {
        isResending.value = true;
        error.value = "";
        success.value = "";

        await authStore.resendVerification();
        success.value = "Verification email has been sent!";
    } catch (err) {
        error.value =
            err.response?.data?.message || "Failed to send verification email";
    } finally {
        isResending.value = false;
    }
};

// Check if we have verification parameters in the URL
const { id, hash } = route.query;
if (id && hash) {
    // Verify the email
    authStore
        .verifyEmail({ id, hash })
        .then(() => {
            success.value = "Email verified successfully! You can now log in.";
            setTimeout(() => {
                router.push("/login");
            }, 3000);
        })
        .catch((err) => {
            error.value =
                err.response?.data?.message || "Failed to verify email";
        });
}
</script>
