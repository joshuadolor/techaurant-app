<template>
    <BaseForm
        :form="form"
        :rules="rules"
        @loading="isSubmitting = $event"
        :submitForm="handleSubmit"
        class="flex flex-col gap-2"
    >
        <BaseFormItem label="Email" prop="email">
            <el-input
                v-model="form.email"
                type="email"
                placeholder="Enter your email"
                :prefix-icon="Message"
                autocomplete="email"
            />
        </BaseFormItem>

        <el-button
            type="primary"
            native-type="submit"
            :loading="isSubmitting"
            class="w-full"
        >
            {{
                isSubmitting
                    ? "Sending reset link..."
                    : "Send Password Reset Link"
            }}
        </el-button>
    </BaseForm>
</template>

<script setup>
import { reactive, ref, computed } from "vue";
import { Message } from "@element-plus/icons-vue";
import { getRules } from "./schema";
import AccountService from "@/services/account";
import { notify } from "@/utils/notification";
import { useRouter } from "vue-router";

const router = useRouter();

const isSubmitting = ref(false);
const form = reactive({
    email: "juan@tamad.com",
});

const rules = computed(() => getRules());

const handleSubmit = async (values) => {
    const data = await AccountService.forgotPassword(values);
    notify.success(data.message);
    router.replace({ name: "login" });
};
</script>
