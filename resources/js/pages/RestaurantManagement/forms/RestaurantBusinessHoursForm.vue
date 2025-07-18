<template>
    <el-form
        ref="formRef"
        :model="{ hours: form }"
        label-position="top"
        @submit.prevent="handleSubmit"
        class="space-y-6"
    >
        <div class="space-y-4">
            <div
                v-for="(day, idx) in form"
                :key="day.day_of_week"
                class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4"
            >
                <span
                    class="w-24 capitalize flex-shrink-0 mb-2 sm:mb-0 text-gray-700"
                    >{{ day.day_of_week }}</span
                >
                <el-switch
                    v-model="day.is_closed"
                    active-text="Closed"
                    inactive-text="Open"
                    active-color="#FF7A1A"
                    inactive-color="#E5E7EB"
                />
                <el-time-picker
                    v-model="day.open_time"
                    :disabled="day.is_closed"
                    placeholder="Open Time"
                    format="HH:mm"
                    value-format="HH:mm:ss"
                    class="w-full sm:w-40 mt-2 sm:mt-0 text-gray-900"
                />
                <el-time-picker
                    v-model="day.close_time"
                    :disabled="day.is_closed"
                    placeholder="Close Time"
                    format="HH:mm"
                    value-format="HH:mm:ss"
                    class="w-full sm:w-40 mt-2 sm:mt-0 text-gray-900"
                />
            </div>
        </div>
        <div
            class="flex flex-col sm:flex-row justify-end gap-4 mt-8 pt-6 border-t border-gray-200"
        >
            <el-button @click="$emit('cancel')" size="large">Cancel</el-button>
            <el-button
                type="primary"
                size="large"
                :loading="loading"
                @click="handleSubmit"
            >
                {{ loading ? "Updating..." : "Update" }}
            </el-button>
        </div>
    </el-form>
</template>

<script setup>
import { ref, watch, inject } from "vue";
import useResourceMethod from "@/composables/useResourceMethod";
import { useRoute } from "vue-router";

const { loading, error, execute } = useResourceMethod("restaurants", {
    method: "update",
});

const props = defineProps({
    modelValue: { type: Array, required: true },
    loading: Boolean,
});

const emit = defineEmits(["update:modelValue", "submit", "cancel"]);

const formRef = ref(null);

// Normalize input to always have open_time/close_time as valid time strings in HH:mm:ss format or undefined
const normalizeTime = (time) => {
    if (!time || typeof time !== "string") return undefined;

    // If already in HH:mm:ss format, return as is
    if (time.match(/^\d{2}:\d{2}:\d{2}$/)) {
        return time;
    }

    // If in HH:mm format, convert to HH:mm:ss
    if (time.match(/^\d{2}:\d{2}$/)) {
        return `${time}:00`;
    }

    return undefined;
};

const normalize = (arr) =>
    arr.map((d) => ({
        ...d,
        open_time: normalizeTime(d.open_time),
        close_time: normalizeTime(d.close_time),
        is_closed: !!d.is_closed,
    }));

const form = ref(normalize(props.modelValue));

watch(
    () => props.modelValue,
    (val) => {
        if (val) form.value = normalize(val);
    },
    { deep: true, immediate: true }
);

const { id } = useRoute().params;
const refreshData = inject("refreshData");

const handleSubmit = async () => {
    const response = await execute(id, { business_hours: form.value });
    if (response) {
        refreshData();
        emit("cancel");
    }
};
</script>
