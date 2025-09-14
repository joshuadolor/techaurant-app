<template>
    <BaseForm
        :form="form"
        :rules="rules"
        @loading="isSubmitting = $event"
        :submitForm="handleSubmit"
        class="menu-item-form"
    >
        <div class="form-header">
            <h2 class="form-title">Create Menu Item</h2>
            <p class="form-subtitle">Add a new item to your menu</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
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

                    <BaseFormItem label="Slug" prop="slug">
                        <el-input
                            v-model="form.slug"
                            placeholder="classic-burger"
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

        <div class="form-section">
            <h3 class="section-title">Categorization</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <BaseFormItem label="Category" prop="menu_category_id">
                    <el-select
                        v-model="form.menu_category_id"
                        placeholder="Select a category"
                        filterable
                        clearable
                        :loading="isFetchingMenus"
                        size="large"
                        class="w-full"
                    >
                        <el-option
                            v-for="category in categories"
                            :key="category.uuid || category.id"
                            :label="category.name"
                            :value="category.uuid || category.id"
                        />
                    </el-select>
                </BaseFormItem>

                <BaseFormItem label="Combo Item" prop="is_combo">
                    <Switch v-model="form.is_combo" active-text="Combo" inactive-text="Single" />
                </BaseFormItem>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 mt-8">
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

const props = defineProps({
    modelValue: { type: Object, required: true },
    submitText: { type: String, default: "Create Item" },
    submitAction: { type: Function, required: true },
});

const emit = defineEmits(["update:modelValue", "back", "submit"]);

const isSubmitting = ref(false);
const form = reactive({ ...props.modelValue });
const rules = computed(() => getRules());

// Auto-generate slug from name
watch(
    () => form.name,
    (val) => {
        if (!form.slug && val) {
            form.slug = val
                .toString()
                .trim()
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, "")
                .replace(/\s+/g, "-")
                .replace(/-+/g, "-");
        }
    }
);

// Fetch categories via menus list (categories are eager-loaded on menus)
const {
    loading: isFetchingMenus,
    execute: fetchMenus,
    data: menus,
} = useApiMethod({ service: "resource/menus", method: "get" });

const categories = computed(() => {
    const list = Array.isArray(menus?.value) ? menus.value : [];
    const set = new Map();
    list.forEach((menu) => {
        (menu.categories || []).forEach((cat) => {
            const key = cat.uuid || cat.id;
            if (!set.has(key)) set.set(key, cat);
        });
    });
    return Array.from(set.values());
});

fetchMenus();

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
    color: #2c3e50;
    margin-bottom: 8px;
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


