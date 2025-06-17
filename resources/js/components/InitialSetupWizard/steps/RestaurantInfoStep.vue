<template>
    <div class="restaurant-info-step">
        <h2 class="text-2xl font-semibold mb-6">Restaurant Information</h2>

        <el-form
            ref="formRef"
            :model="formData"
            :rules="rules"
            label-position="top"
        >
            <!-- Basic Info -->
            <el-form-item label="Restaurant Name" prop="name">
                <el-input
                    v-model="formData.name"
                    placeholder="Enter your restaurant name"
                />
            </el-form-item>

            <el-form-item label="Tagline" prop="tagline">
                <el-input
                    v-model="formData.tagline"
                    placeholder="A short description of your restaurant"
                />
            </el-form-item>

            <el-form-item label="Description" prop="description">
                <el-input
                    v-model="formData.description"
                    type="textarea"
                    rows="4"
                    placeholder="Tell customers about your restaurant"
                />
            </el-form-item>

            <!-- Logo Upload -->
            <el-form-item label="Restaurant Logo" prop="logo">
                <el-upload
                    class="logo-uploader"
                    :show-file-list="false"
                    :auto-upload="false"
                    accept="image/*"
                    @change="handleFileChange"
                >
                    <img
                        v-if="formData.logo"
                        :src="formData.logo"
                        class="logo"
                    />
                    <el-icon v-else class="logo-uploader-icon">
                        <Plus />
                    </el-icon>
                </el-upload>
            </el-form-item>

            <!-- Cropper Dialog -->
            <el-dialog v-model="showCropper" title="Crop Logo" width="800px">
                <Cropper
                    v-if="imageUrl"
                    class="cropper"
                    :src="imageUrl"
                    :stencil-props="{ aspectRatio: 1 }"
                    ref="cropper"
                />
                <template #footer>
                    <el-button @click="showCropper = false">Cancel</el-button>
                    <el-button type="primary" @click="cropImage"
                        >Save</el-button
                    >
                </template>
            </el-dialog>

            <!-- Contact Information -->
            <h3 class="text-lg font-medium mt-8 mb-4">Contact Information</h3>

            <el-form-item label="Phone Number" prop="contact.phone">
                <el-input
                    v-model="formData.contact.phone"
                    placeholder="+1 (555) 555-5555"
                />
            </el-form-item>

            <el-form-item label="Email" prop="contact.email">
                <el-input
                    v-model="formData.contact.email"
                    placeholder="contact@restaurant.com"
                />
            </el-form-item>

            <el-form-item label="Address" prop="contact.address">
                <el-input
                    v-model="formData.contact.address"
                    type="textarea"
                    rows="2"
                    placeholder="Enter your restaurant address"
                />
            </el-form-item>

            <!-- Navigation Buttons -->
            <div class="flex justify-end gap-4 mt-8">
                <el-button @click="$emit('back')">Back</el-button>
                <el-button type="primary" @click="handleNext"
                    >Next: Menu Categories</el-button
                >
            </div>
        </el-form>
    </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import { Plus } from "@element-plus/icons-vue";
import { ElMessage } from "element-plus";
import { Cropper } from "vue-advanced-cropper";
import "vue-advanced-cropper/dist/style.css";

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["update:modelValue", "next", "back"]);

const formRef = ref(null);
const formData = reactive({ ...props.modelValue });

const rules = {
    name: [
        {
            required: true,
            message: "Please enter restaurant name",
            trigger: "blur",
        },
        {
            min: 3,
            max: 50,
            message: "Length should be 3 to 50 characters",
            trigger: "blur",
        },
    ],
    tagline: [
        {
            max: 100,
            message: "Tagline should not exceed 100 characters",
            trigger: "blur",
        },
    ],
    "contact.email": [
        {
            required: true,
            message: "Please enter email address",
            trigger: "blur",
        },
        {
            type: "email",
            message: "Please enter a valid email address",
            trigger: "blur",
        },
    ],
    "contact.phone": [
        {
            required: true,
            message: "Please enter phone number",
            trigger: "blur",
        },
    ],
};

const showCropper = ref(false);
const imageUrl = ref("");
const cropper = ref(null);

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
    formData.logo = canvas.toDataURL();
    showCropper.value = false;
};

const handleNext = async () => {
    if (!formRef.value) return;

    await formRef.value.validate((valid) => {
        if (valid) {
            emit("update:modelValue", formData);
            emit("next");
        } else {
            ElMessage.error("Please fill in all required fields");
        }
    });
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

/* Add cropper styles */
.cropper {
    width: 100%;
    height: 400px;
    background: #eee;
}
</style>
