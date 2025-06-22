<template>
    <BaseForm
        :form="form"
        :rules="rules"
        @loading="isSubmitting = $event"
        :submitForm="handleSubmit"
        class="flex flex-col gap-4"
    >
        <h2 class="text-2xl font-semibold mb-6">Create your Restaurant</h2>

        <!-- Basic Info -->
        <BaseFormItem label="Restaurant Name" prop="name">
            <el-input
                v-model="form.name"
                placeholder="Enter your restaurant name"
            />
        </BaseFormItem>

        <BaseFormItem label="Tagline" prop="tagline">
            <el-input
                v-model="form.tagline"
                placeholder="A short description of your restaurant"
            />
        </BaseFormItem>

        <BaseFormItem label="Description" prop="description">
            <el-input
                v-model="form.description"
                type="textarea"
                rows="4"
                placeholder="Tell customers about your restaurant"
            />
        </BaseFormItem>

        <!-- Logo Upload -->
        <BaseFormItem label="Restaurant Logo" prop="logo">
            <el-upload
                class="logo-uploader"
                :show-file-list="false"
                :auto-upload="false"
                accept="image/*"
                @change="handleFileChange"
            >
                <img v-if="form.logo" :src="form.logo" class="logo" />
                <el-icon v-else class="logo-uploader-icon">
                    <Plus />
                </el-icon>
            </el-upload>
        </BaseFormItem>

        <!-- Cropper Dialog -->
        <el-dialog
            v-model="showCropper"
            title="Logo"
            :fullscreen="isMobile"
            class="cropper-dialog"
        >
            <Cropper
                v-if="imageUrl"
                class="cropper"
                :src="imageUrl"
                :stencil-props="{ aspectRatio: 1 }"
                ref="cropper"
            />
            <template #footer>
                <el-button @click="showCropper = false">Cancel</el-button>
                <el-button type="primary" @click="cropImage">Save</el-button>
            </template>
        </el-dialog>

        <!-- Contact Information -->
        <h3 class="text-lg font-medium mt-8 mb-4">Contact Information</h3>

        <BaseFormItem label="Phone Number" prop="contact.phone">
            <el-input
                v-model="form.contact.phone"
                placeholder="+1 (555) 555-5555"
            />
        </BaseFormItem>

        <BaseFormItem label="Email" prop="contact.email">
            <el-input
                v-model="form.contact.email"
                placeholder="contact@restaurant.com"
            />
        </BaseFormItem>

        <BaseFormItem label="Address" prop="contact.address">
            <el-input
                v-model="form.contact.address"
                type="textarea"
                rows="2"
                placeholder="Enter your restaurant address"
            />
        </BaseFormItem>

        <!-- Navigation Buttons -->
        <div class="flex justify-end gap-4 mt-8">
            <el-button v-if="showBackButton" @click="$emit('back')">
                Back
            </el-button>
            <el-button
                type="primary"
                native-type="submit"
                :loading="isSubmitting"
            >
                {{ isSubmitting ? "Saving..." : submitText }}
            </el-button>
        </div>
    </BaseForm>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from "vue";
import { Plus } from "@element-plus/icons-vue";
import { Cropper } from "vue-advanced-cropper";
import "vue-advanced-cropper/dist/style.css";
import { getRules } from "./schema";

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
        default: "Submit",
    },
});

const emit = defineEmits(["update:modelValue", "back", "submit"]);

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
    window.addEventListener("resize", checkMobile);
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
    form.logo = canvas.toDataURL();
    showCropper.value = false;
};

const handleSubmit = async (values) => {
    emit("update:modelValue", values);
    emit("submit", values);
};
</script>

<style scoped>
.logo-uploader {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    width: 178px;
    height: 178px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.logo-uploader:hover {
    border-color: #409eff;
}

.logo-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    text-align: center;
    line-height: 178px;
}

.logo {
    width: 178px;
    height: 178px;
    display: block;
    object-fit: cover;
}

.cropper {
    width: 100%;
    height: 400px;
    background: #eee;
}

.cropper-dialog {
    /* Add any custom styles for the cropper dialog */
}

/* Mobile-specific cropper styles */
@media (max-width: 768px) {
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
</style>
