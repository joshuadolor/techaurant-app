<template>
    <el-form-item :label="label" :prop="prop" :error="errorMessage">
        <slot></slot>
    </el-form-item>
</template>

<script setup>
import { computed, inject } from "vue";

const apiErrors = inject("apiErrors");
const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    prop: {
        type: String,
        required: true,
    },
});

const errorMessage = computed(() => {
    return apiErrors?.value?.[props.prop];
});
</script>
