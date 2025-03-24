<template>
    <el-form-item
        :label="label"
        :prop="name"
        :error="formHasError ? form.errors[name] : ''"
    >
        <slot></slot>
    </el-form-item>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    form: {
        type: Object,
        required: true,
    },
});

const formHasError = computed(() => {
    return props.form.errors && props.form.errors[props.name];
});
</script>
