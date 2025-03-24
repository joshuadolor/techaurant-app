<template>
    <div class="card w-full max-w-md bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title justify-center text-2xl font-bold">
                Reset Password
            </h2>
            <BaseForm
                ref="formRef"
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
                    />
                </BaseFormItem>

                <BaseFormItem label="New Password" prop="password">
                    <el-input
                        v-model="form.password"
                        type="password"
                        placeholder="Enter your new password"
                        :prefix-icon="Lock"
                        show-password
                    />
                </BaseFormItem>

                <BaseFormItem
                    label="Confirm New Password"
                    prop="password_confirmation"
                >
                    <el-input
                        v-model="form.password_confirmation"
                        type="password"
                        placeholder="Confirm your new password"
                        :prefix-icon="Lock"
                        show-password
                    />
                </BaseFormItem>

                <input type="hidden" name="token" :value="token" />

                <div class="card-actions justify-end">
                    <el-button
                        type="primary"
                        native-type="submit"
                        :loading="isSubmitting"
                        class="w-full"
                    >
                        {{
                            isSubmitting
                                ? "Resetting Password..."
                                : "Reset Password"
                        }}
                    </el-button>
                </div>

                <div class="text-center mt-4">
                    <p class="text-sm">
                        Remember your password?
                        <router-link to="/login" class="link link-primary"
                            >Login</router-link
                        >
                    </p>
                </div>
            </BaseForm>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, computed } from "vue";
import { Message, Lock } from "@element-plus/icons-vue";
import { getResetPasswordRules } from "./schema";
import { useErrorHandler } from "@/composables/useErrorHandler";
import AccountService from "@/services/account";

const { handleError } = useErrorHandler();

const props = defineProps({
    token: {
        type: String,
        required: true,
    },
});

const formRef = ref(null);
const isSubmitting = ref(false);
const form = reactive({
    email: "",
    password: "",
    password_confirmation: "",
    errors: {},
});

const rules = computed(() => getResetPasswordRules(form));

const handleSubmit = async () => {
    try {
        const valid = await formRef.value.validate();
        if (valid) {
            isSubmitting.value = true;
            await AccountService.resetPassword({
                email: form.email,
                password: form.password,
                password_confirmation: form.password_confirmation,
                token: props.token,
            });
            // Show success message
            window.location.href =
                "/login?message=Password has been reset successfully";
        }
    } catch (error) {
        handleError(error, { setValidationErrors: form.errors });
    } finally {
        isSubmitting.value = false;
    }
};

const emit = defineEmits(["submit"]);
</script>
