<template>
    <el-form
        ref="formRef"
        :model="{ hours: form }"
        label-position="top"
        @submit.prevent="handleSubmit"
        class="space-y-6 bg-white p-4 md:p-6 lg:p-8"
    >
        <h2 class="text-lg font-semibold" style="color: #ff7a1a">
            Business Hours
        </h2>
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
                style="
                    background-color: #ff7a1a;
                    border-color: #ff7a1a;
                    color: #fff;
                "
                @click="handleSubmit"
            >
                {{ loading ? "Saving..." : "Save Changes" }}
            </el-button>
        </div>
    </el-form>
</template>

<script setup>
import { ref, watch, computed } from "vue";
const props = defineProps({
    modelValue: { type: Array, required: true },
    loading: Boolean,
});
const emit = defineEmits(["update:modelValue", "submit", "cancel"]);
const formRef = ref(null);

// Normalize input to always have open_time/close_time as string or null
const normalize = (arr) =>
    arr.map((d) => ({
        ...d,
        open_time: typeof d.open_time === "string" ? d.open_time : null,
        close_time: typeof d.close_time === "string" ? d.close_time : null,
        is_closed: !!d.is_closed,
    }));

const form = ref(normalize(props.modelValue));
watch(
    () => props.modelValue,
    (val) => {
        form.value = normalize(val);
    },
    { deep: true }
);
const handleSubmit = async () => {
    emit("update:modelValue", [...form.value]);
    emit("submit", [...form.value]);
};
</script>
