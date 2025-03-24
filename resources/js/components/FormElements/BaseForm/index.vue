<template>
    <el-form
        ref="formRef"
        :model="form"
        :rules="rules"
        :label-position="labelPosition"
        :size="size"
        @submit.prevent="handleSubmit"
        novalidate
        :disabled="loading"
    >
        <!-- Form content -->
        <slot></slot>
    </el-form>
</template>

<script setup>
import { ValidationError } from "@/utils/ErrorHandler";
import { ref, provide } from "vue";

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
    rules: {
        type: Object,
        default: () => ({}),
    },
    submitForm: {
        required: true,
        type: Function,
        default: () => {
            console.log("no submit callback");
        },
    },
    labelPosition: {
        type: String,
        default: "top",
        validator: (value) => ["top", "left", "right"].includes(value),
    },
    size: {
        type: String,
        default: "default",
        validator: (value) => ["small", "default", "large"].includes(value),
    },
    loading: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits([
    "submit",
    "success",
    "error",
    "validation",
    "loading",
]);

const formRef = ref(null);
const loading = ref(false);
const apiErrors = ref({});
provide("apiErrors", apiErrors);

// Expose validate method to parent components
const validate = async () => {
    if (!formRef.value) return false;

    try {
        await formRef.value.validate();
        emit("validation", true);
        return true;
    } catch (error) {
        emit("validation", false);
        return false;
    }
};

const resetForm = () => {
    if (formRef.value) {
        formRef.value.resetFields();
    }
};

const handleSubmit = async () => {
    loading.value = true;
    apiErrors.value = {};
    emit("loading", true);
    try {
        const isValid = await validate();
        if (isValid) {
            emit("submit", props.form);
            await props.submitForm(props.form);
            emit("success");
        }
    } catch (error) {
        emit("error", error);
        if (error instanceof ValidationError) {
            const errors = error.getErrors();
            apiErrors.value = errors;
        }
    } finally {
        loading.value = false;
        emit("loading", false);
    }
};

// Expose methods to parent components
defineExpose({
    validate,
    resetForm,
    formRef,
});
</script>
