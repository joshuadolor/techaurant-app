<template>
    <div class="restaurant-info-step">
        <RestaurantCreate
            :model-value="restaurant"
            :show-back-button="false"
            submit-text="Next: Menu Categories"
        />
    </div>
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
