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
                <div class="card w-full max-w-md flex flex-col gap-4">
                    <h2 class="card-title justify-center text-2xl font-bold">
                        Register
                    </h2>
                    <RegistrationForm />
                </div>
                <SSO />
                <div>
                    <p
                        class="flex gap-2 justify-center items-center text-sm text-gray-600"
                    >
                        Already have an account?
                        <router-link to="/login">
                            <el-link type="primary">Login </el-link>
                        </router-link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import SSO from "@/pages/auth/Login/SSO.vue";
import RegistrationForm from "@/widgets/Forms/Registration";
</script>
