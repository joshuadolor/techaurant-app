<template>
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h2 class="text-xl font-semibold mb-2">Processing login...</h2>
            <p v-if="error" class="text-red-500">{{ error }}</p>
        </div>
    </div>
</template>

<script setup>
import axios from "@/axios";
import { useRouter, useRoute } from "vue-router";
import { ref, onMounted } from "vue";
import { useAuthStore } from "@/stores/auth";

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();
const error = ref(null);

onMounted(async () => {
    try {
        // Get the provider from the URL
        const provider = route.params.provider;

        // Send the callback URL to our backend
        const response = await axios.get(
            `/auth/${provider}/callback${window.location.search}`
        );

        // Store the token and user data
        if (response.data.token) {
            const { token, user } = response.data;
            authStore.setAuthState(user, token);

            // Redirect to dashboard
            router.replace({ name: "dashboard" });
        } else {
            throw new Error("No token received");
        }
    } catch (err) {
        error.value = err.response?.data?.message || "Authentication failed";
        console.error("Social callback error:", err);

        // Redirect to login after error
        setTimeout(() => {
            router.replace({ name: "login" });
        }, 3000);
    }
});
</script>
