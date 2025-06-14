<template>
    <AuthenticatedLayout>
        <div class="flex flex-col items-center justify-center h-screen">
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <p class="text-lg">Welcome, {{ user.name }}</p>
            <div class="flex gap-2 mt-4">
                <button
                    class="bg-blue-500 text-white px-4 py-2 rounded"
                    @click="logout"
                >
                    Logout
                </button>

                <button
                    class="bg-green-700 text-white px-4 py-2 rounded"
                    @click="refresh"
                >
                    Refresh Token
                </button>
            </div>
            <div class="mt-10">
                <h3 class="text-lg font-bold">Change Password</h3>
                <ChangePassword />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { useAuthStore } from "@/stores/auth";
import { computed } from "vue";
import { useRouter } from "vue-router";
import ChangePassword from "@/widgets/Forms/ChangePassword";
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout";

const authStore = useAuthStore();
const router = useRouter();

const user = computed(() => authStore.user || {});

const logout = async () => {
    await authStore.logout();
    router.replace({ name: "login" });
};

const refresh = async () => {
    await authStore.refresh();
};
</script>
