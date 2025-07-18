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
            <el-form-item label="Phone" prop="phone" :error="getError('phone')">
                <el-input
                    v-model="form.phone"
                    placeholder="Enter phone number"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item label="Email" prop="email" :error="getError('email')">
                <el-input
                    v-model="form.email"
                    placeholder="Enter email address"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item
                label="Address"
                prop="address"
                :error="getError('address')"
                class="md:col-span-2"
            >
                <el-input
                    v-model="form.address"
                    type="textarea"
                    :rows="2"
                    placeholder="Enter your restaurant's full address"
                    class="text-gray-900"
                />
            </el-form-item>
            <el-form-item
                label="Country"
                prop="country_id"
                :error="getError('country_id')"
                class="md:col-span-2"
            >
                <el-select
                    v-model="form.country_id"
                    placeholder="Select a country"
                    filterable
                    clearable
                    :loading="isFetchingCountries"
                    class="w-full text-gray-900"
                >
                    <el-option
                        v-for="country in countries"
                        :key="country.id"
                        :label="country.name"
                        :value="country.id"
                    />
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
                @click="handleSubmit"
            >
                {{ loading ? "Updating..." : "Update" }}
            </el-button>
        </div>
    </el-form>
</template>

<script setup>
import { ref, watch, inject, onMounted } from "vue";
import useResourceMethod from "@/composables/useResourceMethod";
import useApiMethod from "@/composables/useApiMethod";
import { useRoute } from "vue-router";

const { loading, error, execute } = useResourceMethod("restaurants", {
    method: "update",
});

const getError = (field) => {
    if (!error) return "";
    return error.value?.getErrors()?.["contact." + field] || "";
};

const {
    loading: isFetchingCountries,
    execute: fetchCountries,
    data: countries,
} = useApiMethod({
    service: "common/countries",
    method: "get",
    shouldCache: true,
});

const props = defineProps({
    modelValue: { type: Object, required: true },
    loading: Boolean,
});

const emit = defineEmits(["update:modelValue", "submit", "cancel"]);

const formRef = ref(null);
const form = ref({ ...props.modelValue });

watch(
    () => props.modelValue,
    (val) => {
        if (val) Object.assign(form.value, val);
        form.value.country_id = val.countryId;
    },
    { deep: true, immediate: true }
);

onMounted(() => {
    fetchCountries();
});

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
