<template>
    <BaseForm
        :form="form"
        :rules="rules"
        @loading="isSubmitting = $event"
        :submitForm="handleSubmit"
        label-position="top"
    >
        <BaseFormItem label="Email" prop="email">
            <el-input
                v-model="form.email"
                type="email"
                placeholder="Enter your email"
                :prefix-icon="Message"
                readonly
            />
        </BaseFormItem>

        <BaseFormItem label="New Password" prop="password">
            <el-input
                v-model="form.password"
                type="password"
                placeholder="Enter your new password"
                :prefix-icon="Lock"
                autocomplete="new-password"
                show-password
            />
        </BaseFormItem>

        <BaseFormItem label="Confirm New Password" prop="password_confirmation">
            <el-input
                v-model="form.password_confirmation"
                type="password"
                placeholder="Confirm your new password"
                :prefix-icon="Lock"
                autocomplete="new-password"
                show-password
            />
        </BaseFormItem>

        <div class="card-actions justify-end">
            <el-button
                type="primary"
                native-type="submit"
                :loading="isSubmitting"
                class="w-full"
            >
                {{ isSubmitting ? "Resetting Password..." : "Reset Password" }}
            </el-button>
        </div>
    </BaseForm>
</template>

<script setup>
import { reactive, ref, computed, watch } from "vue";
import { Message, Lock } from "@element-plus/icons-vue";
import { getRules } from "./schema";
import AccountService from "@/services/account";
import { useRouter } from "vue-router";
import { notify } from "@/utils/notification";

const router = useRouter();

const isSubmitting = ref(false);
const form = reactive({
    email: "",
    password: "123123123",
    password_confirmation: "123123123",
    token: "",
    errors: {},
});

watch(
    () => router.currentRoute.value.query,
    ({ email, token }) => {
        if (!email || !token) {
            router.replace({ name: "login" });
        }
        form.email = email;
        form.token = token;
    },
    { immediate: true }
);

const rules = computed(() => getRules(form));

const handleSubmit = async (values) => {
    const data = await AccountService.resetPassword(values);
    notify.success(data.message);
    router.replace({ name: "login" });
};
</script>
