<template>
    <span
        v-if="shouldShow"
        :id="`${name}-error`"
        class="text-red-500 text-xs mt-1"
    >
        {{ error }}
    </span>
</template>

<script setup>
import { computed, inject } from "vue";
import { FormContextKey } from "vee-validate";

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
});

const form = inject(FormContextKey);
const { errors, submitCount } = form;

const shouldShow = computed(() => {
    return errors.value[props.name] && submitCount.value > 0;
});

const error = computed(() => errors.value[props.name]);
</script>
