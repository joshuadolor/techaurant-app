<template>
    <BaseForm
        class="py-6"
        @loading="isSubmitting = $event"
        :form="form"
        :rules="rules"
        :submitForm="handleSubmit"
    >
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
                show-password
            />
        </BaseFormItem>

        <div class="flex justify-between items-center">
            <label class="label cursor-pointer gap-2">
                <el-checkbox v-model="form.remember" label="Remember me" />
            </label>
            <router-link
                to="/forgot-password"
                class="link link-primary text-sm"
            >
                <el-link type="primary"> Forgot password?</el-link>
            </router-link>
        </div>

        <el-button
            type="primary"
            native-type="submit"
            :loading="isSubmitting"
            class="w-full mt-4"
        >
            {{ isSubmitting ? "Signing in..." : "Sign in" }}
        </el-button>
    </BaseForm>
</template>

<script setup>
import { reactive, ref, computed } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { Message, Lock } from "@element-plus/icons-vue";
import { AccountNotVerifiedError } from "@/utils/ErrorHandler";
import { getRules } from "./schema";

const router = useRouter();
const authStore = useAuthStore();
const isSubmitting = ref(false);

const form = reactive({
    email: "juan@tamad.com",
    password: "password",
    remember: false,
});

const rules = computed(() => getRules());

const handleSubmit = async (values) => {
    try {
        await authStore.login(values);
        router.replace("/dashboard");
    } catch (error) {
        router.replace("/dashboard");
    }
};
</script>
