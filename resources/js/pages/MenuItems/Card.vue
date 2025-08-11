<!-- resources/js/pages/MenuItems/Card.vue -->
<template>
    <div
        class="menu-item-card space-y-3 cursor-pointer"
        @click="navigateToMenuItem"
    >
        <!-- Menu Item Image -->
        <div class="relative aspect-video rounded-lg overflow-hidden">
            <el-image
                :src="item.primaryImageUrl || '/images/menu-item-placeholder.jpg'"
                class="w-full h-full"
                fit="cover"
            >
                <template #error>
                    <div
                        class="flex items-center justify-center h-full bg-gray-100"
                    >
                        <el-icon class="text-gray-400"><Picture /></el-icon>
                    </div>
                </template>
            </el-image>
            
            <!-- Status Badges -->
            <div class="absolute top-2 right-2 flex flex-col gap-1">
                <!-- Active/Inactive Badge -->
                <el-tag
                    :type="item.isActive ? 'success' : 'danger'"
                    effect="dark"
                    size="small"
                    class="shadow-lg shadow-white/50"
                >
                    {{ item.isActive ? "Active" : "Inactive" }}
                </el-tag>
                
                <!-- Available/Unavailable Badge -->
                <el-tag
                    :type="item.isAvailable ? 'success' : 'warning'"
                    effect="dark"
                    size="small"
                    class="shadow-lg shadow-white/50"
                >
                    {{ item.isAvailable ? "Available" : "Unavailable" }}
                </el-tag>
            </div>

            <!-- Price Badge -->
            <div class="absolute bottom-2 right-2">
                <el-tag
                    type="warning"
                    effect="dark"
                    class="shadow-lg shadow-white/50"
                >
                    <span class="font-bold">
                        {{ formatPrice(item.price) }}
                    </span>
                </el-tag>
            </div>

            <!-- Combo Badge -->
            <div v-if="item.isCombo" class="absolute top-2 left-2">
                <el-tag
                    type="info"
                    effect="dark"
                    size="small"
                    class="shadow-lg shadow-white/50"
                >
                    <el-icon class="mr-1"><Star /></el-icon>
                    Combo
                </el-tag>
            </div>
        </div>

        <!-- Menu Item Info -->
        <div class="space-y-2">
            <!-- Name and Description -->
            <div>
                <h3 class="text-lg font-semibold line-clamp-1">
                    {{ item.name }}
                </h3>
                <p class="text-sm text-gray-500 line-clamp-2">
                    {{ item.description || "No description available" }}
                </p>
            </div>

            <!-- Quick Stats -->
            <div class="flex gap-4 text-sm text-gray-600">
                <div class="flex items-center gap-1">
                    <el-icon><Timer /></el-icon>
                    <span>{{ item.preparationTime || 0 }}min</span>
                </div>
                
            </div>
            <div class="flex gap-4 text-sm text-gray-600">
                <div class="flex items-center gap-1">
                    <el-icon><Folder /></el-icon>
                    <span>{{ item.categoriesCount || 0 }} Categories</span>
                </div>
                <div class="flex items-center gap-1">
                    <el-icon><Shop /></el-icon>
                    <span>{{ item.restaurantsCount || 0 }} Restaurants</span>
                </div>
            </div>

            <!-- Categories Tags -->
            <div v-if="item.categories && item.categories.length > 0" class="flex flex-wrap gap-1">
                <el-tag
                    v-for="category in item.categories.slice(0, 3)"
                    :key="category.id"
                    size="small"
                    type="info"
                    effect="plain"
                >
                    {{ category.name }}
                </el-tag>
                <el-tag
                    v-if="item.categories.length > 3"
                    size="small"
                    type="info"
                    effect="plain"
                >
                    +{{ item.categories.length - 3 }} more
                </el-tag>
            </div>

          
        </div>
    </div>
</template>

<script setup>
import { useRouter } from "vue-router";
import {
    Folder,
    Timer,
    Shop,
    Link,
    User,
    Picture,
    Star,
} from "@element-plus/icons-vue";

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const router = useRouter();

const navigateToMenuItem = () => {
    router.push({
        name: "menu-item.view",
        params: {
            id: item.id,
        },
    });
};

// Helper function to format price
const formatPrice = (price) => {
    if (price === null || price === undefined) return "Free";
    return `$${parseFloat(price).toFixed(2)}`;
};
</script>

<style scoped>
.menu-item-card {
    transition: all 0.2s;
}

.menu-item-card:hover {
    transform: scale(1.02);
}

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
