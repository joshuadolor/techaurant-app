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
            >
                <Edit v-if="mode === 'edit'" />
                <Close v-if="mode === 'view'" />
                {{ mode === "edit" ? "Cancel" : "Edit Contact" }}
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
                        {{ restaurant.contact?.phone || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Email</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ restaurant.contact?.email || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Address</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ restaurant.contact?.address || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >City</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ restaurant.contact?.city || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >State</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ restaurant.contact?.state || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >ZIP</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ restaurant.contact?.zip || "—" }}
                    </p>
                </div>
                <div class="flex flex-col space-y-1">
                    <label
                        class="text-xs md:text-sm font-medium text-gray-700 mb-0.5"
                        >Country ID</label
                    >
                    <p class="text-gray-900 text-base md:text-lg">
                        {{ restaurant.contact?.country_id || "—" }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Edit Mode: Show Form -->
        <RestaurantContactForm
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
import { Edit, Close, Location } from "@element-plus/icons-vue";
import { ElMessage } from "element-plus";
import useResourceMethod from "@/composables/useResourceMethod";
import RestaurantContactForm from "../forms/RestaurantContactForm.vue";

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
    phone: "",
    email: "",
    address: "",
    city: "",
    state: "",
    zip: "",
    country_id: "",
});

watch(
    () => mode.value,
    (val) => {
        if (val === "edit") {
            Object.assign(editForm, {
                phone: "123-456-7890",
                email: "dummy@email.com",
                address: "123 Dummy St.",
                city: "Dummytown",
                state: "DummyState",
                zip: "12345",
                country_id: "99",
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
    try {
        isSubmitting.value = true;
        await updateRestaurant(props.restaurant.id, {
            contact: { ...formData },
        });
        ElMessage.success("Contact updated successfully");
        mode.value = "view";
        emit("updated");
    } catch (error) {
        ElMessage.error("Failed to update contact");
    } finally {
        isSubmitting.value = false;
    }
};
</script>
