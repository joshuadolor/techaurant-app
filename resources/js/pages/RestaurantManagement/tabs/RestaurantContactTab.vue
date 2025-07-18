<template>
    <div class="bg-white">
        <div class="flex items-center gap-2 mb-6 justify-between">
            <div class="flex items-center gap-2">
                <el-icon class="text-orange-400"><Location /></el-icon>
                <h3
                    class="text-base md:text-lg lg:text-xl font-semibold text-orange-400"
                >
                    {{
                        mode === "edit"
                            ? "Edit Contact Information"
                            : "Contact Information"
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
                Edit Contact
            </el-button>
        </div>

        <!-- View Mode -->
        <div v-if="mode === 'view'">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-4">
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Phone</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ data?.phone || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Email</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ data?.email || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Address</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ data?.displayAddress || "—" }}
                    </p>
                </div>
                <!-- <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >City</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ data?.city || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >State</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ data?.state || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >ZIP</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ data?.zip || "—" }}
                    </p>
                </div> -->
            </div>
        </div>

        <!-- Edit Mode: Show Form -->
        <RestaurantContactForm
            v-else
            v-model="editForm"
            :loading="isSubmitting"
            @cancel="cancelEdit"
        />
    </div>
</template>

<script setup>
import { ref, reactive, watch } from "vue";
import { Edit, Close, Location } from "@element-plus/icons-vue";
import { ElMessage } from "element-plus";
import useResourceMethod from "@/composables/useResourceMethod";
import RestaurantContactForm from "../forms/RestaurantContactForm.vue";

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["updated"]);

const mode = ref("view");
const isSubmitting = ref(false);

const editForm = reactive({
    phone: "",
    email: "",
    address: "",
    city: "",
    state: "",
    zip: "",
    country_id: "",
});

watch(
    () => ({ mode: mode.value, data: props.data }),
    ({ mode, data }) => {
        if (mode === "edit" && data) {
            Object.assign(editForm, {
                phone: data.phone || "",
                email: data.email || "",
                address: data.rawAddress || "",
                city: data.city || "",
                state: data.state || "",
                zip: data.zip || "",
                countryId: data.countryId || "",
            });
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
