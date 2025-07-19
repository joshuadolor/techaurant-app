<template>
    <el-form
        :model="form"
        label-position="top"
        @submit.prevent="handleSubmit"
        class="space-y-6"
    >
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6">
            <el-form-item
                label="Language"
                prop="language"
                :error="error?.getErrors()?.language"
            >
                <el-select
                    v-model="form.language"
                    placeholder="Select language"
                    class="w-full text-gray-900"
                >
                    <el-option label="English" value="en" />
                    <el-option label="Spanish" value="es" />
                </el-select>
            </el-form-item>
            <el-form-item
                label="Currency"
                prop="currency"
                :error="error?.getErrors()?.currency"
            >
                <el-select
                    v-model="form.currency"
                    placeholder="Select currency"
                    class="w-full text-gray-900"
                >
                    <el-option label="USD ($)" value="USD" />
                    <el-option label="EUR (€)" value="EUR" />
                    <el-option label="PHP (₱)" value="PHP" />
                    <el-option label="GBP (£)" value="GBP" />
                </el-select>
            </el-form-item>
            <el-form-item
                label="Primary Color"
                prop="primary_color"
                :error="error?.getErrors()?.primary_color"
            >
                <el-color-picker v-model="form.primary_color" />
            </el-form-item>
            <el-form-item
                label="Secondary Color"
                prop="secondary_color"
                :error="error?.getErrors()?.secondary_color"
            >
                <el-color-picker v-model="form.secondary_color" />
            </el-form-item>
            <!-- <el-form-item
                label="Logo URL"
                prop="logo_url"
                :error="error?.getErrors()?.logo_url"
            >
                <el-input
                    v-model="form.logo_url"
                    placeholder="Paste logo URL or upload below"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item
                label="Banner URL"
                prop="banner_url"
                :error="error?.getErrors()?.banner_url"
            >
                <el-input
                    v-model="form.banner_url"
                    placeholder="Paste banner URL or upload below"
                    class="text-gray-900"
                />
            </el-form-item> -->
            <!-- <el-form-item
                label="Timezone"
                prop="timezone"
                :error="error?.getErrors()?.timezone"
            >
                <el-select
                    v-model="form.timezone"
                    placeholder="Select timezone"
                    class="w-full text-gray-900"
                >
                    <el-option label="UTC" value="UTC" />
                    <el-option label="EST" value="EST" />
                    <el-option label="PST" value="PST" />
                </el-select>
            </el-form-item> -->
        </div>
        <div
            class="flex flex-col sm:flex-row justify-end gap-4 mt-8 pt-6 border-t border-gray-200"
        >
            <el-button @click="$emit('cancel')" size="large">Cancel</el-button>
            <el-button
                type="primary"
                size="large"
                :loading="loading"
                @click="handleSubmit"
            >
                {{ loading ? "Updating..." : "Update" }}
            </el-button>
        </div>
    </el-form>
</template>

<script setup>
import { ref, watch, inject } from "vue";
import useResourceMethod from "@/composables/useResourceMethod";
import { useRoute } from "vue-router";

const { loading, error, execute } = useResourceMethod("restaurants", {
    method: "update",
});

const props = defineProps({
    modelValue: { type: Object, required: true },
    loading: Boolean,
});

const emit = defineEmits(["update:modelValue", "submit", "cancel"]);

const form = ref({ ...props.modelValue });

watch(
    () => props.modelValue,
    (val) => {
        if (val) Object.assign(form.value, val);
    },
    { deep: true, immediate: true }
);

const { id } = useRoute().params;
const refreshData = inject("refreshData");

const handleSubmit = async () => {
    const response = await execute(id, { config: form.value });
    if (response) {
        refreshData();
        emit("cancel");
    }
};
</script>
