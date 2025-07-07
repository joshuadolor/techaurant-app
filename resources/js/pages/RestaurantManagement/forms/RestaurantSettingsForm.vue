<template>
    <el-form
        ref="formRef"
        :model="form"
        :rules="rules"
        label-position="top"
        @submit.prevent="handleSubmit"
        class="space-y-6 bg-white p-4 md:p-6 lg:p-8"
    >
        <h2 class="text-lg font-semibold" style="color: #ff7a1a">
            Restaurant Settings
        </h2>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6">
            <el-form-item label="Language" prop="language">
                <el-select
                    v-model="form.language"
                    placeholder="Select language"
                    class="w-full text-gray-900"
                >
                    <el-option label="English" value="en" />
                    <el-option label="Spanish" value="es" />
                </el-select>
            </el-form-item>
            <el-form-item label="Primary Color" prop="primary_color">
                <el-color-picker v-model="form.primary_color" />
            </el-form-item>
            <el-form-item label="Secondary Color" prop="secondary_color">
                <el-color-picker v-model="form.secondary_color" />
            </el-form-item>
            <el-form-item label="Logo URL" prop="logo_url">
                <el-input
                    v-model="form.logo_url"
                    placeholder="Paste logo URL or upload below"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item label="Banner URL" prop="banner_url">
                <el-input
                    v-model="form.banner_url"
                    placeholder="Paste banner URL or upload below"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item label="Timezone" prop="timezone">
                <el-select
                    v-model="form.timezone"
                    placeholder="Select timezone"
                    class="w-full text-gray-900"
                >
                    <el-option label="UTC" value="UTC" />
                    <el-option label="EST" value="EST" />
                    <el-option label="PST" value="PST" />
                </el-select>
            </el-form-item>
            <el-form-item label="Currency" prop="currency">
                <el-select
                    v-model="form.currency"
                    placeholder="Select currency"
                    class="w-full text-gray-900"
                >
                    <el-option label="USD ($)" value="USD" />
                    <el-option label="EUR (€)" value="EUR" />
                    <el-option label="GBP (£)" value="GBP" />
                </el-select>
            </el-form-item>
        </div>
        <div
            class="flex flex-col sm:flex-row justify-end gap-4 mt-8 pt-6 border-t border-gray-200"
        >
            <el-button @click="$emit('cancel')" size="large">Cancel</el-button>
            <el-button
                type="primary"
                size="large"
                :loading="loading"
                style="
                    background-color: #ff7a1a;
                    border-color: #ff7a1a;
                    color: #fff;
                "
                @click="handleSubmit"
            >
                {{ loading ? "Saving..." : "Save Changes" }}
            </el-button>
        </div>
    </el-form>
</template>

<script setup>
import { ref, watch, toRefs } from "vue";
const props = defineProps({
    modelValue: { type: Object, required: true },
    loading: Boolean,
});
const emit = defineEmits(["update:modelValue", "submit", "cancel"]);
const formRef = ref(null);
const form = ref({ ...props.modelValue });
const rules = {
    language: [
        { required: true, message: "Language is required", trigger: "change" },
    ],
    primary_color: [
        {
            required: true,
            message: "Primary color is required",
            trigger: "change",
        },
    ],
    secondary_color: [
        {
            required: true,
            message: "Secondary color is required",
            trigger: "change",
        },
    ],
    timezone: [
        { required: true, message: "Timezone is required", trigger: "change" },
    ],
    currency: [
        { required: true, message: "Currency is required", trigger: "change" },
    ],
};
watch(
    () => props.modelValue,
    (val) => {
        form.value = { ...val };
    },
    { deep: true }
);
const handleSubmit = async () => {
    if (!formRef.value) return;
    try {
        await formRef.value.validate();
        emit("update:modelValue", { ...form.value });
        emit("submit", { ...form.value });
    } catch (e) {}
};
</script>
