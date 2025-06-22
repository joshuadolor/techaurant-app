<template>
    <AuthenticatedLayout>
        <div>
            <RestaurantCreate :model-value="restaurant" />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout";
import PageTitle from "@/components/PageTitle";
import RestaurantCreate from "@/components/Restaurant/Forms/Create";

const restaurant = ref({
    name: "",
    tagline: "",
    description: "",
    logo: "",
    contact: {
        phone: "",
        email: "",
        address: "",
    },
});
</script>
