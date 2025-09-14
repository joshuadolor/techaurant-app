<template>
    <!-- Categories Toolbar -->
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-semibold">
            <span v-if="menu.categories.length !== 0" class="text-orange-400">{{ menu.categories.length }}</span>
            {{ menu.categories.length > 1 ? 'Categories' : 'Category' }}
            <span class="text-gray-400" v-if="selectedCategory === null">(none selected)</span>
        </h3>
        <div class="flex gap-2">
            <el-button @click="toggleReorder" :type="reorderCategories ? 'success' : 'primary'">
                {{ reorderCategories ? 'Done' : 'Reorder' }}
            </el-button>
            <el-button :disabled="selectedCategory === null" @click="addItemGlobal">Add Item</el-button>
            <el-button type="primary" @click="addCategory">Add Category</el-button>
        </div>
    </div>

    <!-- Normal edit mode -->
    <div v-if="!reorderCategories" class="space-y-4">
        <el-card v-for="(category, catIndex) in menu.categories" :key="category.__key" shadow="never" class="relative border rounded"
                 :class="{ 'ring-2 ring-orange-400': selectedCategory === catIndex }">
            <!-- Category Header (select row) -->
            <div class="flex items-center gap-3 mb-3 cursor-pointer select-none" @click="selectCategory(catIndex)">
                <el-form-item label="Category Name" :prop="`categories.${catIndex}.name`"
                              :rules="[{ required: true, message: 'Category name is required', trigger: 'blur' }]"
                              class="flex-1 w-full m-0">
                    <el-input v-model="category.name" placeholder="Category name" class="w-full" />
                </el-form-item>
                <el-button type="danger" size="small" @click.stop="removeCategory(catIndex)"
                           class="shrink-0 absolute right-0 top-0">Remove Category</el-button>
            </div>

            <!-- Items header -->
            <div class="flex items-center justify-between mb-2">
                <h4 class="font-medium">Items ({{ category.items.length }})</h4>
                <div class="flex gap-2">
                    <el-button size="small" @click="openAttachDialog(catIndex)">Add Menu Item</el-button>
                    <el-button size="small" @click="addItem(category)">Add Item</el-button>
                </div>
            </div>

            <!-- Items List -->
            <div v-if="!category.items || category.items.length === 0" class="p-4 text-center text-gray-500 border rounded">
                No items. Click "Add Item".
            </div>

            <div v-else class="space-y-2">
                <ItemForm
                    v-for="(item, itemIndex) in category.items"
                    :key="item.__key"
                    :item="item"
                    :item-index="itemIndex"
                    :cat-index="catIndex"
                    compact
                    @remove="removeItem(category, itemIndex)"
                />
            </div>
        </el-card>
    </div>

    <!-- Reorder mode (drag categories only) -->
    <VueDraggableNext v-else v-model="menu.categories" item-key="__key" handle=".drag-handle" :animation="150" class="space-y-3">
        <template #item="{ element: category, index: catIndex }">
            <div class="flex items-center gap-2 p-3 bg-white rounded border">
                <button type="button" class="drag-handle shrink-0 w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 active:bg-gray-200">
                    <el-icon><Rank /></el-icon>
                </button>
                <el-form-item label="Category Name" :prop="`categories.${catIndex}.name`"
                              :rules="[{ required: true, message: 'Category name is required', trigger: 'blur' }]"
                              class="flex-1 w-full m-0">
                    <el-input v-model="category.name" placeholder="Category name" class="w-full" />
                </el-form-item>
            </div>
        </template>
    </VueDraggableNext>
    <!-- Attach existing item dialog -->
    <el-dialog v-model="attachDialogVisible" title="Add Menu Item" width="480px">
        <el-form label-position="top">
            <el-form-item label="Choose an existing item">
                <el-select v-model="selectedItemKey" placeholder="Select item" filterable class="w-full">
                    <el-option v-for="it in catalog" :key="it.__key" :label="it.name" :value="it.__key" />
                </el-select>
            </el-form-item>
        </el-form>
        <template #footer>
            <div class="flex justify-end gap-2">
                <el-button @click="attachDialogVisible = false">Cancel</el-button>
                <el-button type="primary" :disabled="!selectedItemKey" @click="confirmAttach">Attach</el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup>
import { ref } from 'vue';
import { VueDraggableNext } from 'vue-draggable-next';
import { Rank } from '@element-plus/icons-vue';
import ItemForm from '@/components/MenuEditor/ItemForm.vue';

const props = defineProps({
    menu: { type: Object, required: true },
    catalog: { type: Array, default: () => [] },
});

// local state for controlled operations
const reorderCategories = ref(false);
const selectedCategory = ref(0);

const toggleReorder = () => {
    reorderCategories.value = !reorderCategories.value;
};

const selectCategory = (idx) => {
    selectedCategory.value = idx;
};

// key generator for UI-only identity
const genKey = () => (crypto?.randomUUID ? crypto.randomUUID() : String(Date.now() + Math.random()).replace(/\D/g, ''));

const addCategory = () => {
    if (!Array.isArray(props.menu.categories)) props.menu.categories = [];
    props.menu.categories.push({ __key: genKey(), name: '', available: true, items: [
        { __key: genKey(), name: '', price: 0, available: true, description: '', primary_image_url: '', tags: [], skus: [] },
    ] });
    selectedCategory.value = props.menu.categories.length - 1;
};

const removeCategory = (index) => {
    props.menu.categories.splice(index, 1);
    if (selectedCategory.value === index) selectedCategory.value = null;
};

const addItem = (category) => {
    if (!Array.isArray(category.items)) category.items = [];
    category.items.push({ __key: genKey(), name: '', price: 0, available: true, description: '', primary_image_url: '', tags: [], skus: [] });
};

const removeItem = (category, itemIndex) => {
    category.items.splice(itemIndex, 1);
};

const addItemGlobal = () => {
    const idx = selectedCategory.value ?? 0;
    const category = props.menu.categories[idx];
    if (!category) return;
    addItem(category);
};

// Attach existing from catalog
const attachDialogVisible = ref(false);
const selectedItemKey = ref('');
const attachTargetIndex = ref(null);

const openAttachDialog = (catIndex) => {
    attachTargetIndex.value = catIndex;
    selectedItemKey.value = '';
    attachDialogVisible.value = true;
};

const confirmAttach = () => {
    const catIdx = attachTargetIndex.value;
    if (catIdx === null || catIdx === undefined) return;
    const category = props.menu.categories[catIdx];
    if (!category) return;
    const source = props.catalog.find(it => it.__key === selectedItemKey.value);
    if (!source) return;
    if (!Array.isArray(category.items)) category.items = [];
    const clone = JSON.parse(JSON.stringify(source));
    clone.__key = genKey();
    category.items.push(clone);
    attachDialogVisible.value = false;
};
</script>


