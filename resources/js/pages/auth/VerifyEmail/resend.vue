<template>
    <div
        class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8"
        v-if="!hasNeededParams"
    >
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Verify Your Email
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                We've sent a verification link to your email address. Please
                check your inbox and click the link to verify your account.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div
                class="bg-white py-8 px-4 sm:rounded-lg sm:px-10 flex flex-col gap-4"
            >
                <div class="card w-full max-w-md flex flex-col gap-4">
                    <h2 class="card-title justify-center text-2xl font-bold">
                        Email Verification
                    </h2>
                    <VerifyEmailForm />
                </div>
                <div>
                    <p
                        class="flex justify-center gap-2 items-center text-sm text-gray-600"
                    >
                        Already verified?
                        <el-link @click.prevent="logout" type="primary"
                            >Login here</el-link
                        >
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, computed, ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { notify } from "@/utils/notification";
import VerifyEmailForm from "@/widgets/Forms/VerifyEmail";

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

const hasFailedVerification = ref(false);

onMounted(async () => {
    // Check if we have verification parameters in the URL
    const { id, hash } = route.params;
    if (id && hash) {
        try {
            await authStore.verifyEmail({ id, hash });
            notify.success("Email verified successfully! You can now log in.");
            router.replace({ name: "login" });
        } catch (error) {
            notify.error(
                error.response?.data?.message || "Failed to verify email"
            );
            hasFailedVerification.value = true;
        }
    }
});

const logout = () => {
    authStore.logout();
    router.replace({ name: "login" });
};

const hasNeededParams = computed(() => {
    const { id, hash } = route.params;
    return id && hash && !hasFailedVerification.value;
});
</script>
