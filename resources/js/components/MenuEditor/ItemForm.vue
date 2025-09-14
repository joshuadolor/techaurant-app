<template>
    <el-card v-if="!compact" :key="item.__key" body-style="{padding: '16px'}">
        <div class="flex items-center gap-3 mb-3 flex-wrap">
            <button type="button" class="drag-handle shrink-0 w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 active:bg-gray-200">
                <el-icon><Rank /></el-icon>
            </button>
            <el-form-item label="Item Name" :prop="`categories.${catIndex}.items.${itemIndex}.name`" :rules="[{ required: true, message: 'Item name is required', trigger: 'blur' }]" class="grow m-0 min-w-[220px]">
                <el-input v-model="item.name" placeholder="Item name" />
            </el-form-item>
            <el-form-item label="Price" :prop="`categories.${catIndex}.items.${itemIndex}.price`" :rules="priceRules" class="w-32 m-0">
                <el-input v-model.number="item.price" type="number" :min="0" :step="0.01" placeholder="0.00" />
            </el-form-item>
            <el-form-item label="Available" class="m-0">
                <el-switch v-model="item.available" />
            </el-form-item>
            <el-button text type="danger" @click="$emit('remove')" class="shrink-0">Remove</el-button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
            <el-form-item label="Image URL" class="m-0">
                <el-input v-model="item.primary_image_url" placeholder="https://..." />
            </el-form-item>
            <el-form-item label="Description" class="m-0">
                <el-input v-model="item.description" type="textarea" :rows="2" placeholder="Describe the item" />
            </el-form-item>
        </div>

        <div class="mb-3">
            <el-form-item label="Tags" class="m-0">
                <el-input v-model="tagsCsv[item.__key]" placeholder="Salad, Healthy, Vegan" @input="syncTags()" />
            </el-form-item>
        </div>

        <div class="flex items-center justify-between mb-1">
            <span class="font-medium">Variants</span>
            <el-button size="small" @click="$emit('add-group')">Add Group</el-button>
        </div>

        <div v-if="item.skus && item.skus.length" class="space-y-2">
            <slot name="groups"></slot>
        </div>
        <div v-else class="text-gray-500 text-sm">No variant groups. Click "Add Group".</div>
    </el-card>

    <!-- Compact mode: name + remove only -->
    <div v-else class="flex items-center gap-2">
        <el-form-item label="Item Name" :prop="`categories.${catIndex}.items.${itemIndex}.name`" :rules="[{ required: true, message: 'Item name is required', trigger: 'blur' }]" class="grow m-0 min-w-[220px]">
            <el-input v-model="item.name" placeholder="Item name" />
        </el-form-item>
        <el-button text type="danger" size="small" @click="$emit('remove')" class="shrink-0">Remove</el-button>
    </div>
</template>

<script setup>
import { Rank } from '@element-plus/icons-vue';

const props = defineProps({
    item: { type: Object, required: true },
    itemIndex: { type: Number, required: true },
    catIndex: { type: Number, required: true },
    tagsCsv: { type: Object, default: () => ({}) },
    priceRules: { type: Array, default: () => ([]) },
    compact: { type: Boolean, default: false },
});

defineEmits(['remove', 'add-group']);

const syncTags = () => {
    const csv = props.tagsCsv[props.item.__key] || '';
    props.item.tags = csv.split(',').map(t => t.trim()).filter(Boolean);
};
</script>


