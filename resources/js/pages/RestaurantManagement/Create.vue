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
import { useRouter } from "vue-router";

const authStore = useAuthStore();
const router = useRouter();

const { loading, execute: createRestaurant } = useResourceMethod(
    "restaurants",
    {
        method: "create",
    }
);

const restaurant = ref({
    name: "test restaurant " + Math.random().toString(36).substring(2, 15),
    tagline: "",
    description: "",
    logo: "",
    contact: {
        phone: Math.random().toString(36).substring(2, 15),
        email: authStore.getUserEmail,
        address: "",
        country_id: Math.floor(Math.random() * 236) + 1,
    },
});

const handleSubmit = async (data) => {
    const cleanedData = { ...data };
    delete cleanedData.logoPreview;
    const formData = jsonToFormData(cleanedData);
    const restaurant = await createRestaurant(formData, {
        headers: {
            "Content-Type": "multipart/form-data",
        },
    });

    router.replace({
        name: "restaurant.view",
        params: {
            id: restaurant.uuid,
        },
    });
};
</script>
