<template>
    <div class="menu-categories-step">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Menu Categories</h2>
            <el-button type="primary" @click="addCategory">
                <el-icon><Plus /></el-icon>
                Add Category
            </el-button>
        </div>

        <div class="space-y-4">
            <el-card
                v-for="(category, index) in categories"
                :key="index"
                class="category-card"
                shadow="hover"
            >
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <el-input
                            v-model="category.name"
                            placeholder="Category name"
                            class="w-64"
                        />
                    </div>

                    <div class="flex items-center gap-2">
                        <el-button
                            type="danger"
                            circle
                            @click="removeCategory(index)"
                        >
                            <el-icon><Delete /></el-icon>
                        </el-button>
                    </div>
                </div>
            </el-card>
        </div>

        <div class="flex justify-between gap-4 mt-8">
            <el-button @click="$emit('back')">Back</el-button>
            <el-button type="primary" @click="handleNext"
                >Next: Menu Items</el-button
            >
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { Plus, Delete } from "@element-plus/icons-vue";
import { ElMessage } from "element-plus";

const props = defineProps({
    modelValue: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(["update:modelValue", "next", "back"]);

const categories = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
});

const addCategory = () => {
    categories.value.push({ name: "" });
};

const removeCategory = (index) => {
    categories.value.splice(index, 1);
};

const handleNext = () => {
    if (categories.value.some((cat) => !cat.name)) {
        ElMessage.warning("Please fill in all category names");
        return;
    }
    emit("next");
};
</script>

<style scoped>
.category-card {
    transition: all 0.3s ease;
}

.category-card:hover {
    transform: translateY(-2px);
}
</style>
