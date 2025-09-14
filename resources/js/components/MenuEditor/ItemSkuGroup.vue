<template>
    <el-card :key="group.__key" body-style="{padding: '12px'}">
        <div class="flex items-center gap-3 mb-2 flex-wrap">
            <button type="button" class="drag-handle shrink-0 w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 active:bg-gray-200">
                <el-icon><Rank /></el-icon>
            </button>
            <el-form-item label="Group" :prop="`categories.${catIndex}.items.${itemIndex}.skus.${groupIndex}.name`" :rules="[{ required: true, message: 'Group name is required', trigger: 'blur' }]" class="grow m-0 min-w-[200px]">
                <el-input v-model="group.name" placeholder="e.g., Size" />
            </el-form-item>
            <el-button text type="danger" @click="$emit('remove-group')">Remove</el-button>
            <el-button size="small" @click="$emit('add-option')">Add Option</el-button>
        </div>

        <div v-if="group.items && group.items.length" class="space-y-2">
            <slot name="options"></slot>
        </div>
        <div v-else class="text-gray-500 text-sm">No options. Click "Add Option".</div>
    </el-card>
</template>

<script setup>
import { Rank } from '@element-plus/icons-vue';

defineProps({
    group: { type: Object, required: true },
    groupIndex: { type: Number, required: true },
    itemIndex: { type: Number, required: true },
    catIndex: { type: Number, required: true },
});

defineEmits(['add-option', 'remove-group']);
</script>


