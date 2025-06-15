<!-- resources/js/pages/RestaurantManagement/Card.vue -->
<template>
    <div
        class="restaurant-card space-y-3 cursor-pointer"
        @click="navigateToRestaurant"
    >
        <!-- Restaurant Image/Logo -->
        <div class="relative aspect-video rounded-lg overflow-hidden">
            <el-image
                :src="item.cover_image || item.logo"
                fit="cover"
                class="w-full h-full"
            >
                <template #error>
                    <div
                        class="flex items-center justify-center h-full bg-gray-100"
                    >
                        <el-icon class="text-gray-400"><Picture /></el-icon>
                    </div>
                </template>
            </el-image>
            <!-- Quick Status Badge -->
            <div class="absolute top-2 right-2">
                <el-tag
                    :type="item.isActive ? 'success' : 'danger'"
                    size="small"
                    effect="dark"
                >
                    {{ item.isActive ? "Open" : "Closed" }}
                </el-tag>
            </div>
        </div>

        <!-- Restaurant Info -->
        <div class="space-y-2">
            <!-- Name and Cuisine -->
            <div>
                <h3 class="text-lg font-semibold line-clamp-1">
                    {{ item.name }}
                </h3>
                <p class="text-sm text-gray-500">{{ item.cuisine_type }}</p>
            </div>

            <!-- Quick Stats -->
            <div class="flex gap-4 text-sm text-gray-600">
                <div class="flex items-center gap-1">
                    <el-icon><Star /></el-icon>
                    <span>{{ item.rating || "New" }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <el-icon><Timer /></el-icon>
                    <span>{{ item.delivery_time }}min</span>
                </div>
                <div class="flex items-center gap-1">
                    <el-icon><Money /></el-icon>
                    <span>{{ item.min_order_amount }}</span>
                </div>
            </div>

            <!-- Location -->
            <div class="flex items-start gap-2 text-sm text-gray-600">
                <el-icon class="mt-0.5"><Location /></el-icon>
                <span class="line-clamp-2">{{ item.address }}</span>
            </div>

            <!-- Business Hours -->
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <el-icon><Clock /></el-icon>
                <span>{{ formatBusinessHours(item.business_hours) }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from "vue-router";
import {
    Location,
    Clock,
    Star,
    Timer,
    Money,
    Picture,
} from "@element-plus/icons-vue";

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const router = useRouter();

const navigateToRestaurant = () => {
    router.push({
        name: "restaurant.view",
        params: {
            id: props.item.id,
        },
    });
};

// Helper function to format business hours
const formatBusinessHours = (hours) => {
    if (!hours) return "Hours not set";
    // You can implement your own formatting logic here
    return hours;
};
</script>

<style scoped>
.restaurant-card {
    @apply transition-all duration-200;
}

.restaurant-card:hover {
    @apply transform scale-[1.02];
}
</style>
