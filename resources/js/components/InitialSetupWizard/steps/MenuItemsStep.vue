<template>
    <div class="menu-items-step">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Menu Items</h2>
            <el-button type="primary" @click="addItem">
                <el-icon><Plus /></el-icon>
                Add Item
            </el-button>
        </div>

        <el-tabs v-model="activeCategory" class="menu-tabs">
            <el-tab-pane
                v-for="(category, index) in categories"
                :key="index"
                :label="category.name"
                :name="index.toString()"
            >
                <div class="space-y-4">
                    <el-card
                        v-for="(item, itemIndex) in getCategoryItems(index)"
                        :key="itemIndex"
                        class="item-card"
                        shadow="hover"
                    >
                        <div class="grid grid-cols-12 gap-4">
                            <!-- Item Image -->
                            <div class="col-span-4">
                                <el-upload
                                    class="item-image-uploader"
                                    action="/api/upload"
                                    :show-file-list="false"
                                    :on-success="
                                        (res) =>
                                            handleImageSuccess(
                                                res,
                                                index,
                                                itemIndex
                                            )
                                    "
                                >
                                    <img
                                        v-if="item.image"
                                        :src="item.image"
                                        class="item-image"
                                    />
                                    <el-icon
                                        v-else
                                        class="item-image-uploader-icon"
                                        ><Plus
                                    /></el-icon>
                                </el-upload>
                            </div>

                            <!-- Item Details -->
                            <div class="col-span-8">
                                <el-form :model="item" label-position="top">
                                    <el-form-item label="Item Name" required>
                                        <el-input v-model="item.name" />
                                    </el-form-item>

                                    <el-form-item label="Description">
                                        <el-input
                                            v-model="item.description"
                                            type="textarea"
                                            rows="2"
                                        />
                                    </el-form-item>

                                    <div class="grid grid-cols-2 gap-4">
                                        <el-form-item label="Price" required>
                                            <el-input-number
                                                v-model="item.price"
                                                :min="0"
                                                :precision="2"
                                                :step="0.5"
                                            />
                                        </el-form-item>

                                        <el-form-item
                                            label="Preparation Time (minutes)"
                                        >
                                            <el-input-number
                                                v-model="item.prepTime"
                                                :min="0"
                                                :step="5"
                                            />
                                        </el-form-item>
                                    </div>
                                </el-form>
                            </div>
                        </div>
                    </el-card>
                </div>
            </el-tab-pane>
        </el-tabs>

        <div class="flex justify-between gap-4 mt-8">
            <el-button @click="$emit('back')">Back</el-button>
            <el-button type="primary" @click="handleNext"
                >Next: Preview</el-button
            >
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { Plus } from "@element-plus/icons-vue";
import { ElMessage } from "element-plus";

const props = defineProps({
    modelValue: {
        type: Array,
        required: true,
    },
    categories: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(["update:modelValue", "next", "back"]);

const activeCategory = ref("0");
const items = computed({
    get: () => props.modelValue,
    set: (value) => emit("update:modelValue", value),
});

const getCategoryItems = (categoryIndex) => {
    return items.value.filter((item) => item.categoryIndex === categoryIndex);
};

const addItem = () => {
    items.value.push({
        name: "",
        description: "",
        price: 0,
        prepTime: 15,
        image: "",
        categoryIndex: parseInt(activeCategory.value),
    });
};

const handleImageSuccess = (response, categoryIndex, itemIndex) => {
    const item = getCategoryItems(categoryIndex)[itemIndex];
    if (item) {
        item.image = response.url;
    }
};

const handleNext = () => {
    if (items.value.some((item) => !item.name || !item.price)) {
        ElMessage.warning("Please fill in all required fields");
        return;
    }
    emit("next");
};
</script>

<style scoped>
.item-image-uploader {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    width: 100%;
    height: 200px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.item-image-uploader:hover {
    border-color: #409eff;
}

.item-image-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 100%;
    height: 100%;
    text-align: center;
    line-height: 200px;
}

.item-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.menu-tabs {
    margin-top: 2rem;
}

.item-card {
    transition: all 0.3s ease;
}

.item-card:hover {
    transform: translateY(-2px);
}
</style>
