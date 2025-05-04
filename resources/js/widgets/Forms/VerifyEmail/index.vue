<template>
    <BaseForm
        @loading="isSubmitting = $event"
        :form="form"
        :submitForm="handleSubmit"
    >
        <el-button
            type="primary"
            :loading="isSubmitting"
            class="w-full"
            @click="handleSubmit"
        >
            {{ isSubmitting ? "Sending..." : "Resend Verification Email" }}
        </el-button>
    </BaseForm>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import { notify } from "@/utils/notification";

const authStore = useAuthStore();
const isSubmitting = ref(false);

const form = reactive({
    errors: {},
});

const handleSubmit = async () => {
    try {
        await authStore.resendVerification();
        notify.success("Verification email has been sent!");
    } catch (error) {
        notify.error(
            error.response?.data?.message || "Failed to send verification email"
        );
    }
};
</script>
