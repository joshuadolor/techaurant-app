<template>
    <div
        class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8"
    >
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Sign in to your account
            </h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div
                class="bg-white py-8 px-4 sm:rounded-lg sm:px-10 flex flex-col gap-4"
            >
                <div class="w-full max-w-md">
                    <h2 class="justify-center text-2xl font-bold">Login</h2>
                    <LoginForm />
                </div>
                <SSO />
                <div>
                    <p class="text-center text-sm text-gray-600">
                        Don't have an account?
                        <router-link to="/register">
                            <el-link type="primary">Register here</el-link>
                        </router-link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import SSO from "@/pages/auth/Login/SSO.vue";
import LoginForm from "@/widgets/Forms/Login";
</script>
