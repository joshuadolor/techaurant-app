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
            Contact Information
        </h2>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6">
            <el-form-item label="Phone" prop="phone">
                <el-input
                    v-model="form.phone"
                    placeholder="Enter phone number"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item label="Email" prop="email">
                <el-input
                    v-model="form.email"
                    placeholder="Enter email address"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item label="Address" prop="address">
                <el-input
                    v-model="form.address"
                    placeholder="Enter address"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item label="City" prop="city">
                <el-input
                    v-model="form.city"
                    placeholder="Enter city"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item label="State" prop="state">
                <el-input
                    v-model="form.state"
                    placeholder="Enter state"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item label="ZIP" prop="zip">
                <el-input
                    v-model="form.zip"
                    placeholder="Enter ZIP code"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item label="Country" prop="country_id">
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
import { ref, watch } from "vue";
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
