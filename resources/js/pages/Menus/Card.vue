<!-- resources/js/pages/Menus/Card.vue -->
<template>
    <div
        class="menu-card space-y-3 cursor-pointer"
        @click="navigateToMenu"
    >
        <!-- Menu Image -->
        <div class="relative aspect-video rounded-lg overflow-hidden">
            <el-image
                :src="item.primaryImageUrl || '/images/menu-placeholder.jpg'"
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
            <!-- Quick Status Badge -->
            <div class="absolute top-2 right-2">
                <el-tag
                    :type="item.isActive ? 'success' : 'danger'"
                    effect="dark"
                    class="shadow-lg shadow-white/50"
                >
                    {{ item.isActive ? "Active" : "Inactive" }}
                </el-tag>
            </div>
        </div>

        <!-- Menu Info -->
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
                    <el-icon><Folder /></el-icon>
                    <span>{{ item.categoriesCount || 0 }} Categories</span>
                </div>
                <div class="flex items-center gap-1">
                    <el-icon><Food /></el-icon>
                    <span>{{ item.itemsCount || 0 }} Items</span>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { useRouter } from "vue-router";
import {
    Folder,
    Food,
    Calendar,
    Link,
    User,
    Picture,
} from "@element-plus/icons-vue";

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
});

const router = useRouter();

const navigateToMenu = () => {
    router.push({
        name: "menu.view",
        params: {
            id: item.id,
        },
    });
};

</script>

<style scoped>
.menu-card {
    transition: all 0.2s;
}

.menu-card:hover {
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
