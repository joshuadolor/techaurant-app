<template>
    <el-form
        ref="formRef"
        :model="{ hours: form }"
        label-position="top"
        @submit.prevent="handleSubmit"
        class="space-y-6"
    >
        <div class="space-y-6">
            <div
                v-for="(day, dayIdx) in form"
                :key="day.dayOfWeek"
                class="border border-gray-200 rounded-lg p-4"
            >
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium capitalize text-gray-900">
                        {{ dayIdx }}
                    </h3>
                    <el-switch
                        v-model="day.isOpen"
                        active-text="Open"
                        inactive-text="Closed"
                        active-color="#FF7A1A"
                        inactive-color="#E5E7EB"
                    />
                </div>

                <!-- Time Periods -->
                <div class="space-y-3">
                    <div
                        v-for="(period, periodIdx) in day.timePeriods"
                        :key="periodIdx"
                        class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 p-3 bg-gray-50 rounded-md"
                    >
                        <el-time-picker
                            v-model="period.openTime"
                            placeholder="Open Time"
                            format="HH:mm"
                            value-format="HH:mm"
                            class="w-full sm:w-40 text-gray-900"
                            :step="{
                                minutes: 15,
                            }"
                            :disabled="!day.isOpen"
                        />

                        <span class="text-gray-500 text-sm">to</span>

                        <el-time-picker
                            v-model="period.closeTime"
                            placeholder="Close Time"
                            format="HH:mm"
                            value-format="HH:mm"
                            class="w-full sm:w-40 text-gray-900"
                            :step="{
                                minutes: 15,
                            }"
                            :disabled="!day.isOpen"
                        />

                        <el-button
                            v-if="day.timePeriods.length > 1"
                            @click="removeTimePeriod(dayIdx, periodIdx)"
                            type="danger"
                            size="small"
                            circle
                        >
                            <el-icon><Minus /></el-icon>
                        </el-button>
                    </div>

                    <!-- Add Time Period Button -->
                    <el-button
                        @click="addTimePeriod(dayIdx)"
                        type="primary"
                        size="small"
                        plain
                        class="w-full sm:w-auto"
                    >
                        <el-icon><Plus /></el-icon>
                        Add Another Time Period
                    </el-button>
                </div>
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
import { ref, watch, inject, reactive } from "vue";
import { Plus, Minus } from "@element-plus/icons-vue";
import useResourceMethod from "@/composables/useResourceMethod";
import { useRoute } from "vue-router";

const { loading, error, execute } = useResourceMethod("restaurants", {
    method: "update",
});

const props = defineProps({
    modelValue: { type: Object, required: true },
    loading: Boolean,
});

const emit = defineEmits(["update:modelValue", "submit", "cancel"]);

const formRef = ref(null);

const form = reactive({
    Sunday: { timePeriods: [], isOpen: true },
    Monday: { timePeriods: [], isOpen: true },
    Tuesday: { timePeriods: [], isOpen: true },
    Wednesday: { timePeriods: [], isOpen: true },
    Thursday: { timePeriods: [], isOpen: true },
    Friday: { timePeriods: [], isOpen: true },
    Saturday: { timePeriods: [], isOpen: true },
});
watch(
    () => props.modelValue,
    (val) => {
        if (val) {
            for (const [day, dayData] of Object.entries(val)) {
                form[day].timePeriods = dayData.timePeriods;
                form[day].isOpen = !dayData.isClosed;
            }
        }
    },
    { deep: true, immediate: true }
);

const addTimePeriod = (day) => {
    form[day].timePeriods.push({
        openTime: undefined,
        closeTime: undefined,
    });
};

const removeTimePeriod = (day, periodIdx) => {
    form[day].timePeriods.splice(periodIdx, 1);
};

const { id } = useRoute().params;
const refreshData = inject("refreshData");

const handleSubmit = async () => {
    const businessHours = [];
    // Convert back to the format the backend expects
    for (const [day, dayData] of Object.entries(form)) {
        for (const period of dayData.timePeriods) {
            if (period.openTime && period.closeTime) {
                businessHours.push({
                    day_of_week: day,
                    is_closed: !dayData.isOpen,
                    open_time: period.openTime,
                    close_time: period.closeTime,
                });
            }
        }
    }

    const response = await execute(id, { business_hours: businessHours });
    if (response) {
        refreshData();
        emit("cancel");
    }
};
</script>
