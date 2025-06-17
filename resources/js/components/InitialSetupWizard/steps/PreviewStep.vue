<template>
    <div class="preview-step">
        <h2 class="text-2xl font-semibold mb-6">Preview Your Restaurant</h2>

        <!-- Restaurant Info Preview -->
        <el-card class="mb-6">
            <template #header>
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium">Restaurant Information</h3>
                    <el-button text @click="editStep(1)">Edit</el-button>
                </div>
            </template>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <img
                        v-if="wizardData.restaurant.logo"
                        :src="wizardData.restaurant.logo"
                        class="w-32 h-32 object-cover rounded-lg"
                    />
                </div>
                <div>
                    <h4 class="text-xl font-semibold">
                        {{ wizardData.restaurant.name }}
                    </h4>
                    <p class="text-gray-600">
                        {{ wizardData.restaurant.tagline }}
                    </p>
                    <p class="mt-2">{{ wizardData.restaurant.description }}</p>
                </div>
            </div>
        </el-card>

        <!-- Menu Preview -->
        <el-card>
            <template #header>
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium">Menu Preview</h3>
                    <div class="flex gap-2">
                        <el-button text @click="editStep(2)"
                            >Edit Categories</el-button
                        >
                        <el-button text @click="editStep(3)"
                            >Edit Items</el-button
                        >
                    </div>
                </div>
            </template>

            <div class="space-y-6">
                <div
                    v-for="(category, index) in wizardData.categories"
                    :key="index"
                    class="category-preview"
                >
                    <h4 class="text-lg font-medium mb-4">
                        {{ category.name }}
                    </h4>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                    >
                        <el-card
                            v-for="item in getCategoryItems(index)"
                            :key="item.id"
                            class="item-preview"
                            shadow="hover"
                        >
                            <img
                                v-if="item.image"
                                :src="item.image"
                                class="w-full h-48 object-cover rounded-t-lg"
                            />
                            <div class="p-4">
                                <h5 class="font-medium">{{ item.name }}</h5>
                                <p class="text-gray-600 text-sm mt-1">
                                    {{ item.description }}
                                </p>
                                <div
                                    class="flex justify-between items-center mt-2"
                                >
                                    <span class="text-lg font-semibold"
                                        >${{ item.price }}</span
                                    >
                                    <span class="text-sm text-gray-500"
                                        >{{ item.prepTime }}min</span
                                    >
                                </div>
                            </div>
                        </el-card>
                    </div>
                </div>
            </div>
        </el-card>

        <div class="flex justify-between gap-4 mt-8">
            <el-button @click="$emit('back')">Back</el-button>
            <el-button type="primary" @click="handleFinish"
                >Finish Setup</el-button
            >
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    wizardData: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["back", "finish", "edit-step"]);

const getCategoryItems = (categoryIndex) => {
    return props.wizardData.items.filter(
        (item) => item.categoryIndex === categoryIndex
    );
};

const editStep = (step) => {
    emit("edit-step", step);
};

const handleFinish = () => {
    emit("finish");
};
</script>

<style scoped>
.category-preview {
    margin-bottom: 2rem;
}

.item-preview {
    transition: all 0.3s ease;
}

.item-preview:hover {
    transform: translateY(-2px);
}
</style>
