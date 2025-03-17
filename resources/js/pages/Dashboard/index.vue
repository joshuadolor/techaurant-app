<template>
    <div>
        <h1>Dashboard</h1>
        <p>Welcome {{ user.name }}</p>
        <button
            class="bg-blue-500 text-white px-4 py-2 rounded"
            @click="logout"
        >
            Logout
        </button>
    </div>
</template>

<script setup>
import { useAuthStore } from "@/stores/auth";
import { computed } from "vue";
import { useRouter } from "vue-router";

const authStore = useAuthStore();
const router = useRouter();

const user = computed(() => authStore.user || {});

const logout = async () => {
    await authStore.logout();
    router.replace({ name: "login" });
};
</script>
