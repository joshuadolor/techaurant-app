<template>
    <AuthenticatedLayout>
        <div class="flex flex-col h-screen">
            <h1 class="text-2xl font-bold">Users</h1>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout";

const data = [
    {
        title: "Total Users",
        value: 123,
        trend: 10,
    },
    {
        title: "Total Restaurants",
        value: 123,
        trend: 10,
    },
];
</script>
