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
                <ForgotPasswordForm />

                <p
                    class="flex items-center justify-center gap-2 text-center mt-4 text-sm text-gray-600"
                >
                    Don't have an account?
                    <router-link to="/login" class="link link-primary">
                        <el-link type="primary">Login</el-link>
                    </router-link>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import ForgotPasswordForm from "@/widgets/Forms/ForgotPassword";
</script>
