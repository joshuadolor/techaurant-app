<template>
    <el-form
        ref="formRef"
        :model="form"
        label-position="top"
        @submit.prevent="handleSubmit"
        class="space-y-6"
    >
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6">
            <el-form-item
                label="Restaurant Name"
                prop="name"
                class="font-semibold"
                :error="error?.getErrors()?.name"
            >
                <el-input
                    v-model="form.name"
                    placeholder="Restaurant Name"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item label="Tagline" prop="tagline">
                <el-input
                    v-model="form.tagline"
                    placeholder="A short, catchy description of your restaurant"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item label="Subdomain" prop="subdomain">
                <el-input
                    v-model="form.subdomain"
                    placeholder="subdomain"
                    class="text-gray-900"
                    readonly
                />
            </el-form-item>
            <el-form-item label="Status" prop="is_active">
                <Switch
                    v-model="form.is_active"
                    active-text="Active"
                    inactive-text="Inactive"
                />
            </el-form-item>
            <el-form-item
                label="Description"
                prop="description"
                class="md:col-span-2"
            >
                <el-input
                    v-model="form.description"
                    type="textarea"
                    :rows="4"
                    placeholder="Tell customers about your restaurant, cuisine, atmosphere, and what makes you special"
                    class="text-gray-900"
                />
            </el-form-item>
        </div>
        <div
            class="flex flex-col sm:flex-row justify-end mt-8 pt-6 border-t border-gray-200"
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
import Switch from "@/widgets/Switch";
import useResourceMethod from "@/composables/useResourceMethod";
import { useRoute } from "vue-router";

const { loading, error, execute } = useResourceMethod("restaurants", {
    method: "update",
});

const props = defineProps({
    modelValue: { type: Object, required: true },
    loading: Boolean,
});

const emit = defineEmits(["update:modelValue", "submit"]);

const formRef = ref(null);
const form = ref({
    name: "",
    slug: "",
    tagline: "",
    description: "",
    subdomain: "",
    is_active: true,
});

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
    const response = await execute(id, form.value);
    if (response) {
        refreshData();
        emit("cancel");
    }
};
</script>
