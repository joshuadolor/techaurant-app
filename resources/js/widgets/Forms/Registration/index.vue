<template>
    <BaseForm
        @loading="isSubmitting = $event"
        :form="form"
        :rules="rules"
        :submitForm="handleSubmit"
    >
        <BaseFormItem label="Name" prop="name">
            <el-input
                v-model="form.name"
                placeholder="Enter your name"
                :prefix-icon="User"
            />
        </BaseFormItem>

        <BaseFormItem label="Email" prop="email">
            <el-input
                v-model="form.email"
                type="email"
                placeholder="Enter your email"
                :prefix-icon="Message"
            />
        </BaseFormItem>

        <BaseFormItem label="Password" prop="password">
            <el-input
                v-model="form.password"
                type="password"
                placeholder="Enter your password"
                :prefix-icon="Lock"
                autocomplete="new-password"
                show-password
            />
        </BaseFormItem>

        <BaseFormItem label="Confirm Password" prop="password_confirmation">
            <el-input
                v-model="form.password_confirmation"
                type="password"
                placeholder="Confirm your password"
                :prefix-icon="Lock"
                autocomplete="new-password"
                show-password
            />
        </BaseFormItem>

        <ElButton
            type="primary"
            native-type="submit"
            class="w-full mt-4"
            :loading="isSubmitting"
        >
            Create account
        </ElButton>
    </BaseForm>
</template>

<script setup>
import { reactive, ref, computed } from "vue";
import { User, Message, Lock } from "@element-plus/icons-vue";
import { getRules } from "./schema";
import { useAuthStore } from "@/stores/auth";
import { notify } from "@/utils/notification";
import { useRouter } from "vue-router";

const router = useRouter();

const isSubmitting = ref(false);
const authStore = useAuthStore();
const form = reactive({
    name: "Juan Tamad",
    email: "juan@tamad.com",
    password: "password",
    password_confirmation: "password",
});

const rules = computed(() => getRules(form));

const handleSubmit = async (values) => {
    await authStore.register(values);
    notify.success(
        "Registration successful! Please check your email for verification link."
    );
    router.replace({ name: "login" });
};
</script>
