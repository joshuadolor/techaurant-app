<template>
    <BaseForm
        :form="form"
        :rules="rules"
        @loading="isSubmitting = $event"
        :submitForm="handleSubmit"
        class="restaurant-form"
    >
        <!-- Header Section -->
        <div class="form-header">
            <h2 class="form-title">Create your Restaurant</h2>
            <p class="form-subtitle">
                Tell us about your restaurant to get started
            </p>
        </div>

        <!-- Main Form Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Right Column - Logo Upload -->
            <div class="form-section">
                <h3 class="section-title">
                    <el-icon class="section-icon"><Picture /></el-icon>
                    Restaurant Logo
                </h3>

                <div class="logo-upload-container">
                    <BaseFormItem label="Upload Logo" prop="logo">
                        <LogoUploader
                            v-model:logo="form.logo"
                            v-model:logoPreview="form.logoPreview"
                            uploadText="Click to upload logo"
                            uploadHint="PNG, JPG up to 2MB"
                            cropperTitle="Crop Restaurant Logo"
                            imageType="Logo"
                            showTips
                        />
                    </BaseFormItem>
                </div>
            </div>
            <!-- Left Column - Basic Info -->
            <div class="form-section">
                <h3 class="section-title">
                    <el-icon class="section-icon"><House /></el-icon>
                    Basic Information
                </h3>

                <div class="grid grid-cols-1 gap-4">
                    <BaseFormItem label="Restaurant Name" prop="name">
                        <el-input
                            v-model="form.name"
                            placeholder="Enter your restaurant name"
                            size="large"
                        />
                    </BaseFormItem>

                    <BaseFormItem label="Tagline" prop="tagline">
                        <el-input
                            v-model="form.tagline"
                            placeholder="A short, catchy description of your restaurant"
                            size="large"
                        />
                    </BaseFormItem>

                    <BaseFormItem label="Description" prop="description">
                        <el-input
                            v-model="form.description"
                            type="textarea"
                            :rows="6"
                            placeholder="Tell customers about your restaurant, cuisine, atmosphere, and what makes you special"
                            size="large"
                        />
                    </BaseFormItem>
                </div>
            </div>
        </div>

        <!-- Contact Information Section -->
        <div
            class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 mb-6"
        >
            <h3 class="text-lg font-semibold flex items-center gap-2 mb-4">
                <el-icon class="section-icon text-orange-400"
                    ><Location
                /></el-icon>
                Contact Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <BaseFormItem label="Phone Number" prop="contact.phone">
                    <el-input
                        v-model="form.contact.phone"
                        placeholder="+1 (555) 555-5555"
                        size="large"
                    />
                </BaseFormItem>
                <BaseFormItem label="Email" prop="contact.email">
                    <el-input
                        v-model="form.contact.email"
                        placeholder="contact@restaurant.com"
                        size="large"
                    />
                </BaseFormItem>
                <div class="md:col-span-2">
                    <BaseFormItem label="Address" prop="contact.address">
                        <el-input
                            v-model="form.contact.address"
                            type="textarea"
                            :rows="2"
                            placeholder="Enter your restaurant's full address"
                            size="large"
                        />
                    </BaseFormItem>
                </div>
                <div class="md:col-span-2">
                    <BaseFormItem label="Country" prop="contact.country_id">
                        <el-select
                            v-model="form.contact.country_id"
                            placeholder="Select a country"
                            filterable
                            clearable
                            :loading="isFetchingCountries"
                            size="large"
                            class="w-full"
                        >
                            <el-option
                                v-for="country in countries"
                                :key="country.id"
                                :label="country.name"
                                :value="country.id"
                            />
                        </el-select>
                    </BaseFormItem>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div
            class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 mt-8"
        >
            <div
                class="flex flex-col md:flex-row justify-between items-center gap-4"
            >
                <el-button
                    v-if="showBackButton"
                    @click="$emit('back')"
                    size="large"
                    class="w-full md:w-auto"
                >
                    <el-icon><ArrowLeft /></el-icon>
                    Back
                </el-button>
                <el-button
                    type="primary"
                    native-type="submit"
                    :loading="isSubmitting"
                    size="large"
                    class="w-full md:w-auto"
                >
                    <el-icon v-if="!isSubmitting"><Check /></el-icon>
                    {{ isSubmitting ? "Creating Restaurant..." : submitText }}
                </el-button>
            </div>
        </div>
    </BaseForm>
</template>

<script setup>
import { reactive, ref, computed, onMounted, onUnmounted } from "vue";
import {
    Location,
    Picture,
    ArrowLeft,
    Check,
    House,
} from "@element-plus/icons-vue";
import { getRules } from "./schema";
import useApiMethod from "@/composables/useApiMethod";
import LogoUploader from "@/components/FormElements/LogoUploader/index.vue";

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
    },
    showBackButton: {
        type: Boolean,
        default: false,
    },
    submitText: {
        type: String,
        default: "Create Restaurant",
    },
    submitAction: {
        type: Function,
        required: true,
    },
});

const emit = defineEmits(["update:modelValue", "back", "submit"]);

const {
    loading: isFetchingCountries,
    execute: fetchCountries,
    data: countries,
} = useApiMethod({
    service: "common/countries",
    method: "get",
    shouldCache: true,
});

const isSubmitting = ref(false);
const form = reactive({ ...props.modelValue });
const rules = computed(() => getRules());

onMounted(() => {
    fetchCountries();
});

const handleSubmit = async (values) => {
    await props.submitAction(values);
};
</script>

<style scoped>
.restaurant-form {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.form-header {
    text-align: center;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.form-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 8px;
    background: linear-gradient(135deg, #f08a5c, #f97316);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.form-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
    margin: 0;
}

.form-content {
    margin-bottom: 40px;
}

.form-section {
    background: #ffffff;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-icon {
    color: #f08a5c;
    font-size: 1.2rem;
}

.logo-section {
    position: sticky;
    top: 20px;
}

.form-actions {
    background: #ffffff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
}

.action-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .restaurant-form {
        padding: 16px;
    }

    .form-title {
        font-size: 2rem;
    }

    .form-section {
        padding: 20px;
        margin-bottom: 20px;
    }

    .logo-section {
        position: static;
    }

    .action-buttons {
        flex-direction: column;
        gap: 12px;
    }

    .action-buttons .el-button {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .form-title {
        font-size: 1.75rem;
    }

    .form-section {
        padding: 16px;
    }

    .section-title {
        font-size: 1.1rem;
    }
}
</style>
