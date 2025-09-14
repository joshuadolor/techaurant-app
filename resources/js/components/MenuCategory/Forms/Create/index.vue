<template>
    <BaseForm
        :form="form"
        :rules="rules"
        @loading="isSubmitting = $event"
        :submitForm="handleSubmit"
        class="menu-category-form"
    >
        <div class="form-header">
            <h2 class="form-title gradient-title">Create Menu Category</h2>
            <p class="form-subtitle">Group related items for easier browsing</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="form-section">
                <h3 class="section-title">Basic Information</h3>
                <div class="grid grid-cols-1 gap-4">
                    <BaseFormItem label="Category Name" prop="name">
                        <el-input
                            v-model="form.name"
                            placeholder="e.g., Burgers"
                            size="large"
                        />
                    </BaseFormItem>

                    <BaseFormItem label="Description" prop="description">
                        <el-input
                            v-model="form.description"
                            type="textarea"
                            :rows="6"
                            placeholder="Short description of the category"
                            size="large"
                        />
                    </BaseFormItem>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Status & Image</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <BaseFormItem label="Active" prop="is_active">
                        <Switch v-model="form.is_active" active-text="Active" inactive-text="Inactive" />
                    </BaseFormItem>

                    <BaseFormItem label="Available" prop="is_available">
                        <Switch v-model="form.is_available" active-text="Available" inactive-text="Unavailable" />
                    </BaseFormItem>

                    <BaseFormItem label="Primary Image URL" prop="primary_image_url" class="md:col-span-2">
                        <el-input
                            v-model="form.primary_image_url"
                            placeholder="https://..."
                            size="large"
                        />
                    </BaseFormItem>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 mt-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <el-button @click="$emit('back')" size="large" class="w-full md:w-auto">
                    Back
                </el-button>
                <el-button type="primary" native-type="submit" :loading="isSubmitting" size="large" class="w-full md:w-auto">
                    {{ isSubmitting ? "Creating Category..." : submitText }}
                </el-button>
            </div>
        </div>
    </BaseForm>
</template>

<script setup>
import { reactive, ref, computed } from "vue";
import Switch from "@/widgets/Switch";
import { getRules } from "./schema";

const props = defineProps({
    modelValue: { type: Object, required: true },
    submitText: { type: String, default: "Create Category" },
    submitAction: { type: Function, required: true },
});

const emit = defineEmits(["update:modelValue", "back", "submit"]);

const isSubmitting = ref(false);
const form = reactive({ ...props.modelValue });
const rules = computed(() => getRules());

const handleSubmit = async (values) => {
    await props.submitAction(values);
};
</script>

<style scoped>
.menu-category-form {
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
    color: #2c3e50;
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


