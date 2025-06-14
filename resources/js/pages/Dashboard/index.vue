<template>
    <AuthenticatedLayout>
        <div class="flex flex-col h-screen">
            <h1 class="text-2xl font-bold">Dashboard Overview</h1>

            <div class="flex flex-wrap gap-4 mt-4 flex-col md:flex-row">
                <div
                    v-for="item in data"
                    :key="item.title"
                    class="rounded-xl border border-gray-200 bg-white p-5 flex flex-col gap-2 w-64"
                >
                    <div class="flex items-start justify-between">
                        <div class="text-gray-600 font-medium">
                            {{ item.title }}
                        </div>
                        <div class="text-2xl text-gray-400">
                            <slot name="icon">
                                <!-- fallback icon -->
                                <span>👥</span>
                            </slot>
                        </div>
                    </div>
                    <div class="text-3xl font-bold">{{ item.value }}</div>
                    <div class="flex items-center gap-2 text-sm">
                        <span
                            :class="4 > 0 ? 'text-green-500' : 'text-red-500'"
                        >
                            <span v-if="item.trend > 0">▲</span>
                            <span v-else-if="item.trend < 0">▼</span>
                            {{ Math.abs(item.trend) }}%
                        </span>
                        <span class="text-gray-400">from last month</span>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { useAuthStore } from "@/stores/auth";
import { computed } from "vue";
import { useRouter } from "vue-router";
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
