<template>
    <BaseForm
        :form="form"
        :rules="rules"
        @loading="isSubmitting = $event"
        :submitForm="handleSubmit"
        :class="['menu-item-form', { 'dialog-form': inDialog }]"
    >
        <div v-if="!inDialog" class="form-header">
            <h2 class="form-title gradient-title">Create Menu Item</h2>
            <p class="form-subtitle">Add a new item to your menu</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="form-section">
                <h3 class="section-title">Basic Information</h3>
                <div class="grid grid-cols-1 gap-4">
                    <BaseFormItem label="Item Name" prop="name">
                        <el-input
                            v-model="form.name"
                            placeholder="e.g., Classic Burger"
                            size="large"
                        />
                    </BaseFormItem>

                    <BaseFormItem label="Description" prop="description">
                        <el-input
                            v-model="form.description"
                            type="textarea"
                            :rows="6"
                            placeholder="Short description of the item"
                            size="large"
                        />
                    </BaseFormItem>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Pricing & Availability</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <BaseFormItem label="Price ($)" prop="price">
                        <el-input
                            v-model.number="form.price"
                            placeholder="0.00"
                            size="large"
                            type="number"
                            :min="0"
                            :step="0.01"
                        />
                    </BaseFormItem>

                    <BaseFormItem label="Prep Time (min)" prop="preparation_time">
                        <el-input
                            v-model.number="form.preparation_time"
                            placeholder="e.g., 10"
                            size="large"
                            type="number"
                            :min="0"
                            :step="1"
                        />
                    </BaseFormItem>

                    <BaseFormItem label="Active" prop="is_active">
                        <Switch v-model="form.is_active" active-text="Active" inactive-text="Inactive" />
                    </BaseFormItem>

                    <BaseFormItem label="Available" prop="is_available">
                        <Switch v-model="form.is_available" active-text="Available" inactive-text="Unavailable" />
                    </BaseFormItem>
                </div>
            </div>

            <!-- Image (mobile-friendly uploader) -->
            <div class="form-section sm:col-span-2">
                <h3 class="section-title">Item Image</h3>
                <div>
                    <BaseFormItem label="Upload Image" prop="primary_image_url">
                        <LogoUploader
                            v-model:logo="form.primary_image_url"
                            v-model:logoPreview="form.primary_image_url"
                            uploadText="Tap to upload image"
                            uploadHint="PNG, JPG up to 2MB"
                            cropperTitle="Crop Item Image"
                            imageType="Primary Image"
                            showTips
                        />
                    </BaseFormItem>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">Variants (SKUs)</h3>
            <div class="space-y-4">
                <el-card v-for="(v, index) in form.skus" :key="index" class="border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <BaseFormItem label="Group" :prop="`skus.${index}.name`">
                            <el-input v-model="v.name" placeholder="e.g., Size" />
                        </BaseFormItem>
                        <BaseFormItem label="Option Label" :prop="`skus.${index}.description`">
                            <el-input v-model="v.description" placeholder="e.g., Kiddie / Normal / Grande" />
                        </BaseFormItem>
                        <BaseFormItem label="SKU Code" :prop="`skus.${index}.sku`">
                            <el-input v-model="v.sku" placeholder="e.g., HMBR-KID" />
                        </BaseFormItem>

                        <BaseFormItem label="Variant Price ($)" :prop="`skus.${index}.price`">
                            <el-input v-model.number="v.price" type="number" :min="0" :step="0.01" placeholder="Leave blank to use item price" />
                        </BaseFormItem>
                        <BaseFormItem label="Active" :prop="`skus.${index}.is_active`">
                            <Switch v-model="v.is_active" />
                        </BaseFormItem>
                        <BaseFormItem label="Available" :prop="`skus.${index}.is_available`">
                            <Switch v-model="v.is_available" />
                        </BaseFormItem>

                        <BaseFormItem label="Sort Order" :prop="`skus.${index}.sort_order`">
                            <el-input v-model.number="v.sort_order" type="number" :min="0" :step="1" />
                        </BaseFormItem>
                        <BaseFormItem label="Image URL" :prop="`skus.${index}.primary_image_url`" class="md:col-span-2">
                            <el-input v-model="v.primary_image_url" placeholder="https://..." />
                        </BaseFormItem>
                    </div>
                    <div class="flex justify-end mt-3">
                        <el-button type="danger" text @click="removeSku(index)">Remove</el-button>
                    </div>
                </el-card>
                <div class="flex justify-start">
                    <el-button type="primary" link @click="addSku">+ Add Variant</el-button>
                </div>
            </div>
        </div>

        <div v-if="!inDialog" class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 mt-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <el-button @click="$emit('back')" size="large" class="w-full md:w-auto">
                    Back
                </el-button>
                <el-button type="primary" native-type="submit" :loading="isSubmitting" size="large" class="w-full md:w-auto">
                    {{ isSubmitting ? "Creating Item..." : submitText }}
                </el-button>
            </div>
        </div>
    </BaseForm>
    
</template>

<script setup>
import { reactive, ref, computed, watch } from "vue";
import Switch from "@/widgets/Switch";
import { getRules } from "./schema";
import useApiMethod from "@/composables/useApiMethod";
import LogoUploader from "@/components/FormElements/LogoUploader/index.vue";

const props = defineProps({
    modelValue: { type: Object, required: true },
    submitText: { type: String, default: "Create Item" },
    submitAction: { type: Function, required: true },
    inDialog: { type: Boolean, default: false },
});

const emit = defineEmits(["update:modelValue", "back", "submit"]);

const isSubmitting = ref(false);
const form = reactive({ ...props.modelValue });
const rules = computed(() => getRules());

// Initialize variants array
if (!Array.isArray(form.skus)) {
    form.skus = [
        {
            name: "Size",
            description: "",
            sku: "",
            price: null,
            is_active: true,
            is_available: true,
            sort_order: 0,
            primary_image_url: "",
        },
    ];
}

const addSku = () => {
    form.skus.push({
        name: "Size",
        description: "",
        sku: "",
        price: null,
        is_active: true,
        is_available: true,
        sort_order: 0,
        primary_image_url: "",
    });
};

const removeSku = (index) => {
    form.skus.splice(index, 1);
};

const handleSubmit = async (values) => {
    await props.submitAction(values);
};
</script>

<style scoped>
.menu-item-form {
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
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.gradient-title {
    background: linear-gradient(135deg, #f08a5c, #f97316);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.form-subtitle {
    font-size: 1rem;
    color: #6c757d;
    margin: 0;
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
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 16px;
}
</style>


