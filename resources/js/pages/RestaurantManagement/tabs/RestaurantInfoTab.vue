<template>
    <div class="bg-white">
        <div class="flex items-center gap-2 mb-6 justify-between">
            <div class="flex items-center gap-2">
                <el-icon class="text-orange-400"><House /></el-icon>
                <h3
                    class="text-base md:text-lg lg:text-xl font-semibold text-orange-400"
                >
                    {{
                        mode === "edit"
                            ? "Edit Restaurant Information"
                            : "Restaurant Information"
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
                Edit Info
            </el-button>
        </div>

        <!-- View Mode -->
        <div v-if="mode === 'view'">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-4">
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Name</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ restaurant.name || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Slug</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ restaurant.slug || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Tagline</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ restaurant.tagline || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Subdomain</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ restaurant.subdomain || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Active</label
                    >
                    <p
                        class="text-gray-900 text-base md:text-lg"
                        :class="{ 'text-red-500': !restaurant.isActive }"
                    >
                        {{ restaurant.isActive ? "Active" : "Inactive" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1 lg:col-span-2">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Description</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ restaurant.description || "—" }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Edit Mode: Show Form -->
        <RestaurantInfoForm
            v-else
            v-model="editForm"
            :loading="isSubmitting"
            @submit="handleFormSubmit"
            @cancel="cancelEdit"
        />
    </div>
</template>

<script setup>
import { ref, reactive, watch } from "vue";
import { Close, Edit, House } from "@element-plus/icons-vue";
import { ElMessage } from "element-plus";
import useResourceMethod from "@/composables/useResourceMethod";
import RestaurantInfoForm from "../forms/RestaurantInfoForm.vue";

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
    name: "",
    slug: "",
    tagline: "",
    description: "",
    subdomain: "",
    is_active: true,
});

watch(
    () => mode.value,
    (val) => {
        if (val === "edit" && props.restaurant) {
            editForm.value = props.restaurant;
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

const handleFormSubmit = async (formData) => {
    isSubmitting.value = true;
    setTimeout(() => {
        isSubmitting.value = false;
        mode.value = "view";
        ElMessage.success("Info updated (dummy)");
        emit("updated");
    }, 1000);
};
</script>
