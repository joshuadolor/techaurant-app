<template>
    <div class="bg-white">
        <div class="flex items-center gap-2 mb-6 justify-between">
            <div class="flex items-center gap-2">
                <el-icon class="text-orange-400"><Clock /></el-icon>
                <h3
                    class="text-base md:text-lg lg:text-xl font-semibold text-orange-400"
                >
                    {{
                        mode === "edit"
                            ? "Edit Business Hours"
                            : "Business Hours"
                    }}
                </h3>
            </div>
            <el-button
                :type="mode === 'edit' ? 'default' : 'primary'"
                @click="toggleMode"
                class="flex items-center gap-2 text-white"
                v-if="mode === 'view'"
            >
                <Edit v-if="mode === 'edit'" />
                Edit Hours
            </el-button>
        </div>
        <!-- View Mode -->
        <div v-if="mode === 'view'">
            <div v-if="data && Object.keys(data).length > 0" class="space-y-4">
                <div
                    v-for="[day, dayData] in Object.entries(data)"
                    :key="day"
                    class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border border-gray-200 rounded-lg gap-y-2"
                >
                    <div class="flex items-center gap-3">
                        <span
                            class="font-medium text-gray-700 text-base md:text-lg"
                            >{{ day }}</span
                        >
                        <el-tag
                            v-if="dayData.data.isOpen"
                            style="
                                background-color: #ff7a1a;
                                color: #fff;
                                border: none;
                            "
                            size="small"
                            >Open</el-tag
                        >
                        <el-tag
                            v-else
                            type="info"
                            style="
                                background-color: #e5e7eb;
                                color: #6b7280;
                                border: none;
                            "
                            size="small"
                            >Closed</el-tag
                        >
                    </div>
                    <div
                        v-if="dayData.data.isOpen"
                        class="text-gray-900 text-base md:text-lg"
                    >
                        <span class="text-gray-500 mr-2">Hours:</span>
                        <div class="space-y-1">
                            <div
                                v-for="(period, index) in dayData.timePeriods"
                                :key="index"
                                class="flex items-center gap-2"
                            >
                                <span
                                    >{{ period.openTime12h }} -
                                    {{ period.closeTime12h }}</span
                                >
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-gray-400 text-base md:text-lg">
                        <span class="text-gray-500 mr-2">Status:</span>Closed
                    </div>
                </div>
            </div>
            <div v-else class="text-center text-gray-400 py-8">
                <el-icon size="large" class="mb-4 text-gray-300"
                    ><Clock
                /></el-icon>
                <p>No business hours configured</p>
            </div>
        </div>
        <!-- Edit Mode: Show Form -->
        <RestaurantBusinessHoursForm
            v-else
            v-model="editForm"
            :loading="isSubmitting"
            @cancel="cancelEdit"
        />
    </div>
</template>

<script setup>
import { ref, reactive, watch } from "vue";
import { Edit, Clock, Close } from "@element-plus/icons-vue";
import { ElMessage } from "element-plus";
import useResourceMethod from "@/composables/useResourceMethod";
import RestaurantBusinessHoursForm from "../forms/RestaurantBusinessHoursForm.vue";

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["updated"]);

const mode = ref("view");
const isSubmitting = ref(false);

const editForm = ref([]);

watch(
    () => ({ mode: mode.value, data: props.data }),
    ({ mode, data }) => {
        if (mode === "edit" && data) {
            editForm.value = data;
        }
    },
    { immediate: true, deep: true }
);

const toggleMode = () => {
    mode.value = mode.value === "view" ? "edit" : "view";
};

const cancelEdit = () => {
    mode.value = "view";
};
</script>
