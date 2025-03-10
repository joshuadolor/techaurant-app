<template>
    <div>
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">
                    Or continue with
                </span>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-3 gap-3">
            <button
                @click="handleLogin('google')"
                type="button"
                class="cursor-pointer w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
            >
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path
                        fill="#EA4335"
                        d="M12 5.04c2.17 0 4.1.78 5.63 2.07l4.2-4.2C19.26 0.64 15.88-.12 12 0 7.38.12 3.4 2.34 1.07 5.68l4.98 3.86C7.14 6.84 9.4 5.04 12 5.04z"
                    />
                    <path
                        fill="#4285F4"
                        d="M23.49 12.27c0-.79-.07-1.54-.19-2.27H12v4.51h6.47c-.29 1.48-1.14 2.73-2.4 3.58v3h3.86c2.26-2.09 3.56-5.17 3.56-8.82z"
                    />
                    <path
                        fill="#FBBC05"
                        d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.86-3c-1.08.72-2.45 1.15-4.07 1.15-3.13 0-5.78-2.11-6.73-4.96L.32 17.3C2.38 21.3 6.84 24 12 24z"
                    />
                    <path
                        fill="#34A853"
                        d="M5.27 14.29c-.25-.72-.38-1.49-.38-2.29s.14-1.57.38-2.29V6.69H.32C-.12 8.37-.24 10.15.24 12c.37 1.5 1.05 2.91 1.97 4.14l4.98-3.85z"
                    />
                </svg>
            </button>

            <button
                @click="handleLogin('facebook')"
                type="button"
                class="cursor-pointer w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
            >
                <svg class="w-5 h-5" fill="#1877F2" viewBox="0 0 24 24">
                    <path
                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                    />
                </svg>
            </button>

            <button
                @click="handleLogin('instagram')"
                type="button"
                class="cursor-pointer w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
            >
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path
                        fill="url(#instagram-gradient)"
                        d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.897 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.897-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"
                    />
                    <defs>
                        <linearGradient
                            id="instagram-gradient"
                            x1="0%"
                            y1="100%"
                            x2="100%"
                            y2="0%"
                        >
                            <stop offset="0%" style="stop-color: #405de6" />
                            <stop offset="50%" style="stop-color: #e1306c" />
                            <stop offset="100%" style="stop-color: #ffdc80" />
                        </linearGradient>
                    </defs>
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onUnmounted } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";

const router = useRouter();
const authStore = useAuthStore();
const socialWindow = ref(null);

const handleLogin = async (provider) => {
    try {
        // Get the OAuth URL from our backend using the store
        const url = await authStore.handleSocialLogin(provider);
        window.location.href = url;
    } catch (error) {
        console.error("Failed to initiate SSO login:", error);
    }
};

const handleSocialCallback = (event) => {
    // Verify the origin of the message
    if (event.origin !== window.location.origin) return;

    const { token, user } = event.data;
    if (token && user) {
        // Store auth data in Pinia
        authStore.setAuth(token, user);

        // Close the popup
        if (socialWindow.value) {
            socialWindow.value.close();
        }

        // Remove the event listener
        window.removeEventListener("message", handleSocialCallback);

        // Redirect to dashboard
        router.push("/dashboard");
    }
};

// Cleanup on component unmount
onUnmounted(() => {
    window.removeEventListener("message", handleSocialCallback);
});
</script>
