<template>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-2 items-center">
        <button type="button" class="drag-handle w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 active:bg-gray-200 md:col-span-1">
            <el-icon><Rank /></el-icon>
        </button>
        <el-form-item label="Option" :prop="`categories.${catIndex}.items.${itemIndex}.skus.${groupIndex}.items.${optIndex}.name`" :rules="[{ required: true, message: 'Option name is required', trigger: 'blur' }]">
            <el-input v-model="option.name" placeholder="e.g., Small" />
        </el-form-item>
        <el-form-item label="Price" :prop="`categories.${catIndex}.items.${itemIndex}.skus.${groupIndex}.items.${optIndex}.price`" :rules="priceRules">
            <el-input v-model.number="option.price" type="number" :min="0" :step="0.01" placeholder="Price" />
        </el-form-item>
        <el-form-item label="Description">
            <el-input v-model="option.description" placeholder="Description" />
        </el-form-item>
        <div class="flex items-center gap-2">
            <el-form-item label="Avail." class="m-0">
                <el-switch v-model="option.available" />
            </el-form-item>
            <el-form-item label="Sort" :prop="`categories.${catIndex}.items.${itemIndex}.skus.${groupIndex}.items.${optIndex}.sort_order`" :rules="sortRules" class="w-20 m-0">
                <el-input v-model.number="option.sort_order" type="number" :min="0" :step="1" placeholder="0" />
            </el-form-item>
            <el-button text type="danger" @click="$emit('remove-option')">Remove</el-button>
        </div>
    </div>
</template>

<script setup>
import { Rank } from '@element-plus/icons-vue';

defineProps({
    option: { type: Object, required: true },
    optIndex: { type: Number, required: true },
    groupIndex: { type: Number, required: true },
    itemIndex: { type: Number, required: true },
    catIndex: { type: Number, required: true },
    priceRules: { type: Array, required: true },
    sortRules: { type: Array, required: true },
});

defineEmits(['remove-option']);
</script>


