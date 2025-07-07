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
            >
                <Edit v-if="mode === 'edit'" />
                <Close v-if="mode === 'view'" />
                {{ mode === "edit" ? "Cancel" : "Edit Settings" }}
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
                        {{ restaurant.config?.language || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Primary Color</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ restaurant.config?.primary_color || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Secondary Color</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ restaurant.config?.secondary_color || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Logo URL</label
                    >
                    <p class="text-gray-900 break-all text-base md:text-lg">
                        {{ restaurant.config?.logo_url || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Banner URL</label
                    >
                    <p class="text-gray-900 break-all text-base md:text-lg">
                        {{ restaurant.config?.banner_url || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Timezone</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ restaurant.config?.timezone || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Currency</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ restaurant.config?.currency || "—" }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Edit Mode: Show Form -->
        <RestaurantSettingsForm
            v-else
            v-model="editForm"
            :loading="isSubmitting"
            @submit="handleFormSubmit"
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
    restaurant: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["updated"]);

const mode = ref("view");
const isSubmitting = ref(false);

const editForm = reactive({
    language: "English",
    primary_color: "#FF0000",
    secondary_color: "#00FF00",
    logo_url: "https://dummy.logo/url.png",
    banner_url: "https://dummy.banner/url.png",
    timezone: "UTC",
    currency: "USD",
});

watch(
    () => mode.value,
    (val) => {
        if (val === "edit") {
            Object.assign(editForm, {
                language: "English",
                primary_color: "#FF0000",
                secondary_color: "#00FF00",
                logo_url: "https://dummy.logo/url.png",
                banner_url: "https://dummy.banner/url.png",
                timezone: "UTC",
                currency: "USD",
            });
        }
    },
    { immediate: true }
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
