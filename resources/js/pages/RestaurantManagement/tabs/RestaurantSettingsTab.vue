<template>
    <div class="bg-white">
        <div class="flex items-center gap-2 mb-6 justify-between">
            <div class="flex items-center gap-2">
                <el-icon class="text-orange-400"><Setting /></el-icon>
                <h3
                    class="text-base md:text-lg lg:text-xl font-semibold text-orange-400"
                >
                    {{
                        mode === "edit"
                            ? "Edit Restaurant Settings"
                            : "Restaurant Settings"
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
                Edit Settings
            </el-button>
        </div>

        <!-- View Mode -->
        <div v-if="mode === 'view'">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-4">
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Language</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ data?.language || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Currency</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ data?.currency || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Primary Color</label
                    >
                    <div
                        class="text-gray-900 text-base md:text-lg flex items-center gap-2"
                    >
                        <div
                            class="w-10 h-10 rounded-md"
                            :style="{ backgroundColor: data?.primaryColor }"
                        ></div>
                        {{ data?.primaryColor || "—" }}
                    </div>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Secondary Color</label
                    >
                    <div
                        class="text-gray-900 text-base md:text-lg flex items-center gap-2"
                    >
                        <div
                            class="w-10 h-10 rounded-md"
                            :style="{ backgroundColor: data?.secondaryColor }"
                        ></div>
                        {{ data?.secondaryColor || "—" }}
                    </div>
                </div>
                <!-- <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Logo URL</label
                    >
                    <p class="text-gray-900 break-all text-base md:text-lg">
                        {{ data?.logoUrl || "—" }}
                    </p>
                </div> -->
                <!-- <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Banner URL</label
                    >
                    <p class="text-gray-900 break-all text-base md:text-lg">
                        {{ data?.banner_url || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Timezone</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ data?.timezone || "—" }}
                    </p>
                </div> -->
            </div>
        </div>

        <!-- Edit Mode: Show Form -->
        <RestaurantSettingsForm
            v-else
            v-model="editForm"
            :loading="isSubmitting"
            @cancel="cancelEdit"
        />
    </div>
</template>

<script setup>
import { ref, reactive, computed, watch } from "vue";
import { Edit, Close, Setting } from "@element-plus/icons-vue";
import { ElMessage } from "element-plus";
import useResourceMethod from "@/composables/useResourceMethod";
import RestaurantSettingsForm from "../forms/RestaurantSettingsForm.vue";

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["updated"]);

const mode = ref("view");
const isSubmitting = ref(false);

const editForm = ref({ ...props.data });

watch(
    () => ({ mode: mode.value, data: props.data }),
    ({ mode, data }) => {
        if (mode === "edit") {
            editForm.value.primary_color = data.primaryColor;
            editForm.value.secondary_color = data.secondaryColor;
            editForm.value.language = data.languageCode;
            editForm.value.currency = data.currency;
            console.log(editForm.value);
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

const { execute: updateRestaurant } = useResourceMethod("restaurants", {
    method: "update",
});

const handleFormSubmit = async (formData) => {
    isSubmitting.value = true;
    setTimeout(() => {
        isSubmitting.value = false;
        mode.value = "view";
        ElMessage.success("Settings updated (dummy)");
        emit("updated");
    }, 1000);
};
</script>
