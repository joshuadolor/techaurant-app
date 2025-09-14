<template>
    <div class="logo-upload-container mx-auto">
        <el-upload
            class="logo-uploader flex justify-center items-center p-4"
            :show-file-list="false"
            :auto-upload="false"
            accept="image/*"
            @change="handleFileChange"
        >
            <div
                class="logo-upload-area"
                :class="logoPreview || logo ? 'not-dashed has-image' : ''"
            >
                <img
                    v-if="logoPreview || logo"
                    :src="
                        logoPreview ||
                        (logo instanceof File
                            ? URL.createObjectURL(logo)
                            : logo)
                    "
                    class="logo-preview"
                    :alt="altText"
                />
                <!-- Hover Overlay -->
                <div v-if="logoPreview || logo" class="hover-overlay">
                    <el-icon class="hover-icon">
                        <Picture />
                    </el-icon>
                    <p class="hover-text">Click to update</p>
                </div>

                <div v-else class="logo-placeholder p-4">
                    <el-icon class="logo-uploader-icon">
                        <Picture />
                    </el-icon>
                    <p class="upload-text">
                        {{ uploadText }}
                    </p>
                    <p class="upload-hint">
                        {{ uploadHint }}
                    </p>
                </div>
            </div>
        </el-upload>

        <div v-if="showTips" class="logo-tips">
            <h4>{{ tipsTitle }}:</h4>
            <ul>
                <li v-for="tip in tips" :key="tip">{{ tip }}</li>
            </ul>
        </div>
    </div>

    <!-- Cropper Dialog -->
    <el-dialog
        v-model="showCropper"
        :title="cropperTitle"
        :fullscreen="isMobile"
        class="cropper-dialog"
        width="800px"
    >
        <div class="flex justify-center items-center min-h-[400px]">
            <Cropper
                v-if="imageUrl"
                class="w-full h-[400px] bg-gray-100 rounded-lg"
                :src="imageUrl"
                :stencil-props="{ aspectRatio: cropAspectRatio }"
                ref="cropper"
            />
        </div>
        <template #footer>
            <div class="flex justify-end gap-1">
                <el-button @click="showCropper = false">Cancel</el-button>
                <el-button type="primary" @click="cropImage"> Save </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Plus, Picture } from "@element-plus/icons-vue";
import { Cropper } from "vue-advanced-cropper";
import "vue-advanced-cropper/dist/style.css";

const props = defineProps({
    logo: {
        type: [String, File, Object],
        default: null,
    },
    logoPreview: {
        type: String,
        default: "",
    },
    uploadText: {
        type: String,
        default: "Click to upload logo",
    },
    uploadHint: {
        type: String,
        default: "PNG, JPG up to 2MB",
    },
    altText: {
        type: String,
        default: "Uploaded logo",
    },
    showTips: {
        type: Boolean,
        default: false,
    },
    tipsTitle: {
        type: String,
        default: "Logo Tips",
    },
    tips: {
        type: Array,
        default: () => [
            "Use a square image (1:1 ratio)",
            "High resolution (at least 200x200px)",
            "Clear background works best",
            "Keep file size under 2MB",
        ],
    },
    cropperTitle: {
        type: String,
        default: "Crop Image",
    },
    imageType: {
        type: String,
        default: "Logo",
    },
    cropAspectRatio: {
        type: Number,
        default: 1,
    },
    enableCropping: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(["update:logo", "update:logoPreview", "change"]);

const showCropper = ref(false);
const imageUrl = ref("");
const cropper = ref(null);
const isMobile = ref(false);

// Detect mobile device
const checkMobile = () => {
    isMobile.value = window.innerWidth <= 768;
};

onMounted(() => {
    checkMobile();
    window.addEventListener("resize", checkMobile);
});

onUnmounted(() => {
    window.removeEventListener("resize", checkMobile);
});

const handleFileChange = (file) => {
    if (props.enableCropping) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imageUrl.value = e.target.result;
            showCropper.value = true;
        };
        reader.readAsDataURL(file.raw);
    } else {
        // Direct upload without cropping
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = e.target.result;
            emit("update:logo", file.raw);
            emit("update:logoPreview", preview);
            emit("change", { file: file.raw, preview });
        };
        reader.readAsDataURL(file.raw);
    }
};

const cropImage = () => {
    const { canvas } = cropper.value.getResult();

    // Convert canvas to blob
    canvas.toBlob((blob) => {
        // Create a File object from the blob
        const file = new File([blob], "cropped-image.png", {
            type: "image/png",
            lastModified: Date.now(),
        });

        const preview = canvas.toDataURL();

        emit("update:logo", file);
        emit("update:logoPreview", preview);
        emit("change", { file, preview });

        showCropper.value = false;
    }, "image/png");
};
</script>

<style scoped>
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
    display: flex;
    justify-content: center;
    align-items: center;
    transition: all 0.3s ease;
    background: #fafafa;
    height: 200px;
    width: 200px;
}

.logo-upload-area.not-dashed {
    border: 2px solid #ccc;
}

.logo-upload-area.has-image {
    position: relative;
}

.logo-upload-area:hover {
    border-color: #f08a5c;
    background: #fff5f2;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(240, 138, 92, 0.15);
}

/* Hover Overlay Styles */
.hover-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    border-radius: 8px;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 2;
}

.logo-upload-area:hover .hover-overlay {
    opacity: 1;
}

.hover-icon {
    font-size: 32px;
    color: #ffffff;
    margin-bottom: 8px;
}

.hover-text {
    color: #ffffff;
    font-size: 1rem;
    font-weight: 500;
    margin: 0;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
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

/* Responsive Design */
@media (max-width: 768px) {
    .logo-upload-area {
        height: 200px;
        width: 200px;
    }

    .logo-uploader-icon {
        font-size: 36px;
    }

    .upload-text {
        font-size: 1rem;
    }

    .hover-icon {
        font-size: 28px;
    }

    .hover-text {
        font-size: 0.9rem;
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
    .logo-tips {
        padding: 12px;
    }

    .logo-tips h4 {
        font-size: 0.9rem;
    }

    .logo-tips li {
        font-size: 0.8rem;
    }

    .hover-icon {
        font-size: 24px;
    }

    .hover-text {
        font-size: 0.8rem;
    }
}
</style>
