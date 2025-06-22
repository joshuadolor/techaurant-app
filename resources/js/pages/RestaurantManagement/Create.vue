<template>
    <AuthenticatedLayout>
        <div>
            <RestaurantCreate
                :model-value="restaurant"
                :submitAction="handleSubmit"
            />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout";
import PageTitle from "@/components/PageTitle";
import RestaurantCreate from "@/components/Restaurant/Forms/Create";
import useResourceMethod from "@/composables/useResourceMethod";
import { jsonToFormData } from "@/utils/formData";
import { useAuthStore } from "@/stores/auth";

const authStore = useAuthStore();

const { loading, execute: createRestaurant } = useResourceMethod(
    "restaurants",
    {
        method: "create",
    }
);

const restaurant = ref({
    name: "",
    tagline: "",
    description: "",
    logo: "",
    contact: {
        phone: "",
        email: authStore.getUserEmail,
        address: "",
    },
});

const handleSubmit = async (data) => {
    const formData = jsonToFormData(data);
    await createRestaurant(formData, {
        headers: {
            "Content-Type": "multipart/form-data",
        },
    });
};
</script>
