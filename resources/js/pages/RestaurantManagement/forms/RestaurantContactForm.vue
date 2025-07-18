<template>
    <el-form
        ref="formRef"
        :model="form"
        :rules="rules"
        label-position="top"
        @submit.prevent="handleSubmit"
        class="space-y-6"
    >
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6">
            <el-form-item
                label="Phone"
                prop="phone"
                :error="error?.getErrors()?.phone"
            >
                <el-input
                    v-model="form.phone"
                    placeholder="Enter phone number"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item
                label="Email"
                prop="email"
                :error="error?.getErrors()?.email"
            >
                <el-input
                    v-model="form.email"
                    placeholder="Enter email address"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item
                label="Address"
                prop="address"
                :error="error?.getErrors()?.address"
            >
                <el-input
                    v-model="form.address"
                    placeholder="Enter address"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item
                label="City"
                prop="city"
                :error="error?.getErrors()?.city"
            >
                <el-input
                    v-model="form.city"
                    placeholder="Enter city"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item
                label="State"
                prop="state"
                :error="error?.getErrors()?.state"
            >
                <el-input
                    v-model="form.state"
                    placeholder="Enter state"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item
                label="ZIP"
                prop="zip"
                :error="error?.getErrors()?.zip"
            >
                <el-input
                    v-model="form.zip"
                    placeholder="Enter ZIP code"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item
                label="Country"
                prop="country_id"
                :error="error?.getErrors()?.country_id"
            >
                <el-input
                    v-model="form.country_id"
                    placeholder="Enter country ID"
                    class="text-gray-900"
                />
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

const formRef = ref(null);
const form = ref({ ...props.modelValue });

const rules = {
    phone: [{ required: true, message: "Phone is required", trigger: "blur" }],
    email: [{ required: true, message: "Email is required", trigger: "blur" }],
};

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
    const response = await execute(id, { contact: form.value });
    if (response) {
        refreshData();
        emit("cancel");
    }
};
</script>
