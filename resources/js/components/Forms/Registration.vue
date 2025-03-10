<template>
    <form @submit.prevent="register" class="space-y-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">
                Name
            </label>
            <div class="mt-1">
                <input
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
            </div>
            <div v-if="errors.name" class="mt-1 text-sm text-red-600">
                {{ errors.name[0] }}
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
                    v-model="form.email"
                    required
                    autocomplete="email"
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
            </div>
            <div v-if="errors.email" class="mt-1 text-sm text-red-600">
                {{ errors.email[0] }}
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
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
            </div>
            <div v-if="errors.password" class="mt-1 text-sm text-red-600">
                {{ errors.password[0] }}
            </div>
        </div>

        <div>
            <label
                for="password_confirmation"
                class="block text-sm font-medium text-gray-700"
            >
                Confirm Password
            </label>
            <div class="mt-1">
                <input
                    id="password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    v-model="form.password_confirmation"
                    required
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
            </div>
            <div
                v-if="errors.password_confirmation"
                class="mt-1 text-sm text-red-600"
            >
                {{ errors.password_confirmation[0] }}
            </div>
        </div>

        <div>
            <button
                type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                Register
            </button>
        </div>
    </form>
</template>

<script setup>
import { ref } from "vue";
import axios from "@/axios";

const form = ref({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const errors = ref({});

const register = async () => {
    try {
        errors.value = {};
        const response = await axios.post("/register", form.value);
        if (response.data.token) {
            localStorage.setItem("token", response.data.token);
            window.location.href = "/dashboard";
        }
    } catch (error) {
        if (error.response?.status === 422) {
            this.errors = error.response.data.errors;
        } else {
            console.error("Registration failed:", error);
        }
    }
};
</script>
