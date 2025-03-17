<template>
    <div
        class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8"
    >
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Create your account
            </h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <RegistrationForm />

                <div class="mt-6">
                    <SSO />
                </div>

                <div class="mt-6">
                    <p class="text-center text-sm text-gray-600">
                        Already have an account?
                        <router-link
                            to="/login"
                            class="font-medium text-indigo-600 hover:text-indigo-500"
                        >
                            Sign in
                        </router-link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import RegistrationForm from "@/components/Forms/Registration/index.vue";
import SSO from "@/pages/auth/Login/SSO.vue";
</script>
