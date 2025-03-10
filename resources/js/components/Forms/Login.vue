<template>
    <form @submit.prevent="login" class="space-y-6">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
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
                    autocomplete="current-password"
                    v-model="form.password"
                    required
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input
                    id="remember-me"
                    type="checkbox"
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
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                Sign in
            </button>
        </div>
    </form>
</template>

<script setup>
import { reactive } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";

const router = useRouter();
const authStore = useAuthStore();

const form = reactive({
    email: "",
    password: "",
});

const login = async () => {
    try {
        await authStore.login(form);
        router.push("/dashboard");
    } catch (error) {
        console.error("Login failed:", error.response?.data?.errors || error);
    }
};
</script>
