<template>
    <BaseForm
        :form="form"
        :rules="rules"
        @loading="isSubmitting = $event"
        :submitForm="handleSubmit"
        class="flex flex-col gap-2"
    >
        <BaseFormItem label="Current Password" prop="current_password">
            <el-input
                v-model="form.current_password"
                type="password"
                placeholder="Enter your current password"
                :prefix-icon="Message"
                show-password

            />
        </BaseFormItem>

        <BaseFormItem label="New Password" prop="password">
            <el-input
                v-model="form.password"
                type="password"
                placeholder="Enter your new password"
                :prefix-icon="Message"
                show-password

            />
        </BaseFormItem>

        <BaseFormItem label="Confirm New Password" prop="password_confirmation">
            <el-input
                v-model="form.password_confirmation"
                type="password"
                placeholder="Enter your new password"
                :prefix-icon="Message"
                show-password

            />
        </BaseFormItem>

        <el-button
            type="primary"
            native-type="submit"
            :loading="isSubmitting"
            class="w-full"
        >
            Submit
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
const defaultForm = {
    current_password: "",
    password: "",
    password_confirmation: "",
};
const form = reactive(defaultForm);

const rules = computed(() => getRules(form));

const resetForm = () => {
    Object.assign(form, {...defaultForm});
};

const handleSubmit = async (values) => {
    const data = await AccountService.changePassword(values);
    notify.success(data.message);
    resetForm();
};
</script>
