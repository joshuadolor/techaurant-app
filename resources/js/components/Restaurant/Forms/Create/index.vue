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
                            :rows="4"
                            placeholder="Tell customers about your restaurant, cuisine, atmosphere, and what makes you special"
                            size="large"
                        />
                    </BaseFormItem>
                </div>
            </div>

            <!-- Right Column - Logo Upload -->
            <div class="form-section">
                <h3 class="section-title">
                    <el-icon class="section-icon"><Picture /></el-icon>
                    Restaurant Logo
                </h3>

                <div class="logo-upload-container">
                    <BaseFormItem label="Upload Logo" prop="logo">
                        <el-upload
                            class="logo-uploader"
                            :show-file-list="false"
                            :auto-upload="false"
                            accept="image/*"
                            @change="handleFileChange"
                        >
                            <div class="logo-upload-area">
                                <img
                                    v-if="form.logoPreview || form.logo"
                                    :src="
                                        form.logoPreview ||
                                        (form.logo instanceof File
                                            ? URL.createObjectURL(form.logo)
                                            : form.logo)
                                    "
                                    class="logo-preview"
                                />
                                <div v-else class="logo-placeholder">
                                    <el-icon class="logo-uploader-icon">
                                        <Plus />
                                    </el-icon>
                                    <p class="upload-text">
                                        Click to upload logo
                                    </p>
                                    <p class="upload-hint">
                                        PNG, JPG up to 2MB
                                    </p>
                                </div>
                            </div>
                        </el-upload>
                    </BaseFormItem>

                    <div class="logo-tips">
                        <h4>Logo Tips:</h4>
                        <ul>
                            <li>Use a square image (1:1 ratio)</li>
                            <li>High resolution (at least 200x200px)</li>
                            <li>Clear background works best</li>
                            <li>Keep file size under 2MB</li>
                        </ul>
                    </div>
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

        <!-- Cropper Dialog -->
        <el-dialog
            v-model="showCropper"
            title="Crop Restaurant Logo"
            :fullscreen="isMobile"
            class="cropper-dialog"
            width="800px"
        >
            <div class="flex justify-center items-center min-h-[400px]">
                <Cropper
                    v-if="imageUrl"
                    class="w-full h-[400px] bg-gray-100 rounded-lg"
                    :src="imageUrl"
                    :stencil-props="{ aspectRatio: 1 }"
                    ref="cropper"
                />
            </div>
            <template #footer>
                <div class="flex justify-end gap-3">
                    <el-button @click="showCropper = false">Cancel</el-button>
                    <el-button type="primary" @click="cropImage"
                        >Save Logo</el-button
                    >
                </div>
            </template>
        </el-dialog>

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
    Plus,
    Location,
    Picture,
    ArrowLeft,
    Check,
    House,
} from "@element-plus/icons-vue";
import { Cropper } from "vue-advanced-cropper";
import "vue-advanced-cropper/dist/style.css";
import { getRules } from "./schema";
import useApiMethod from "@/composables/useApiMethod";

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
});

const isSubmitting = ref(false);
const form = reactive({ ...props.modelValue });
const isMobile = ref(false);

const rules = computed(() => getRules());

const showCropper = ref(false);
const imageUrl = ref("");
const cropper = ref(null);

// Detect mobile device
const checkMobile = () => {
    isMobile.value = window.innerWidth <= 768;
};

onMounted(() => {
    checkMobile();
    fetchCountries();
    window.addEventListener("resize", checkMobile);
});

onUnmounted(() => {
    window.removeEventListener("resize", checkMobile);
});

const handleFileChange = (file) => {
    const reader = new FileReader();
    reader.onload = (e) => {
        imageUrl.value = e.target.result;
        showCropper.value = true;
    };
    reader.readAsDataURL(file.raw);
};

const cropImage = () => {
    const { canvas } = cropper.value.getResult();

    // Convert canvas to blob
    canvas.toBlob((blob) => {
        // Create a File object from the blob
        const file = new File([blob], "cropped-logo.png", {
            type: "image/png",
            lastModified: Date.now(),
        });

        // Store the file object instead of base64
        form.logo = file;

        // Also store the preview URL for display
        form.logoPreview = canvas.toDataURL();

        showCropper.value = false;
    }, "image/png");
};

const handleSubmit = async (values) => {
    console.log(values);
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

.logo-upload-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.logo-uploader {
    width: 100%;
}

.logo-upload-area {
    border: 2px dashed #d9d9d9;
    border-radius: 12px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    width: 100%;
    height: 200px;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: all 0.3s ease;
    background: #fafafa;
}

.logo-upload-area:hover {
    border-color: #f08a5c;
    background: #fff5f2;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(240, 138, 92, 0.15);
}

.logo-placeholder {
    text-align: center;
    color: #8c939d;
}

.logo-uploader-icon {
    font-size: 48px;
    color: #f08a5c;
    margin-bottom: 12px;
}

.upload-text {
    font-size: 1.1rem;
    font-weight: 500;
    margin: 8px 0 4px 0;
    color: #2c3e50;
}

.upload-hint {
    font-size: 0.9rem;
    color: #6c757d;
    margin: 0;
}

.logo-preview {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
}

.logo-tips {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 16px;
    border-left: 4px solid #f08a5c;
}

.logo-tips h4 {
    margin: 0 0 12px 0;
    color: #2c3e50;
    font-size: 1rem;
    font-weight: 600;
}

.logo-tips ul {
    margin: 0;
    padding-left: 20px;
    color: #6c757d;
}

.logo-tips li {
    margin-bottom: 6px;
    font-size: 0.9rem;
}

.cropper-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 400px;
}

.cropper {
    width: 100%;
    height: 400px;
    background: #f5f5f5;
    border-radius: 8px;
}

.cropper-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
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

    .cropper {
        height: calc(100vh - 200px);
        min-height: 300px;
    }

    .cropper-dialog .el-dialog {
        margin: 0;
        height: 100vh;
        max-height: 100vh;
    }

    .cropper-dialog .el-dialog__body {
        padding: 10px;
        height: calc(100vh - 120px);
        overflow: hidden;
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
