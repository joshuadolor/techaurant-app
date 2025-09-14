<template>
    <AuthenticatedLayout>
        <div class="max-w-6xl mx-auto p-4 space-y-6">
            <div class="flex items-center justify-between">
                <PageTitle>Menu Editor</PageTitle>
                <div class="flex items-center gap-2">
                    <el-button  @click="toggleReorder">Reorder</el-button>
                    <el-button @click="copyJson">Copy JSON</el-button>
                    <el-button type="primary" @click="validateAll">Validate</el-button>
                </div>
            </div>

            <el-form ref="formRef" :model="menu" label-position="top">
                <!-- Menu Name -->
                <el-card class="mb-5">
                    <div class="flex items-center gap-3">
                        <el-form-item label="Menu Name" prop="name" :rules="[{ required: true, message: 'Menu name is required', trigger: 'blur' }]" class="flex-1">
                            <el-input v-model="menu.name" placeholder="Menu name (e.g., Main Menu)" />
                        </el-form-item>
                    </div>
                    <CategoryForm :menu="menu" :catalog="catalog" />
                </el-card>

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Menu Items Catalog</h3>
                    <el-button type="primary" @click="openCreateDialog">Create a Menu Item</el-button>
                </div>

                <el-dialog v-model="createDialogVisible" title="Create a Menu Item" width="520px">
                    <el-form label-position="top">
                        <el-form-item label="Name">
                            <el-input v-model="draftItem.name" placeholder="e.g., Classic Burger" />
                        </el-form-item>
                    </el-form>
                    <template #footer>
                        <div class="flex justify-end gap-2">
                            <el-button @click="createDialogVisible = false">Cancel</el-button>
                            <el-button type="primary" :disabled="!draftItem.name" @click="createItem">Create</el-button>
                        </div>
                    </template>
                </el-dialog>

                <div class="space-y-2 mb-8">
                    <div v-if="catalog.length === 0" class="text-gray-500">No menu items yet.</div>
                    <div v-for="it in catalog" :key="it.__key" class="flex items-center gap-2 p-2 border rounded">
                        <span class="text-sm text-gray-500">Item</span>
                        <el-input v-model="it.name" class="grow" />
                        <el-button text type="danger" @click="removeFromCatalog(it.__key)">Remove</el-button>
                    </div>
                </div>

            </el-form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout';
import PageTitle from '@/components/PageTitle';
import { useRoute } from 'vue-router';
import { ref, onMounted, watch } from 'vue';
import { ElMessage } from 'element-plus';
import { Rank, Menu } from '@element-plus/icons-vue';
import { VueDraggableNext } from 'vue-draggable-next';
import ItemForm from '@/components/MenuEditor/ItemForm.vue';
import CategoryForm from '@/components/MenuEditor/CategoryForm.vue';

const Draggable = VueDraggableNext;

const route = useRoute();
const restaurantId = route.params.restaurantId;

// Reorder state for categories list
const reorderCategories = ref(false);
const toggleReorder = () => {
    reorderCategories.value = !reorderCategories.value;
};

// Selection (controlled add blocks)
const selectedCategory = ref(0);
const selectCategory = (idx) => {
    selectedCategory.value = idx;
};

// Local key generator for UI-only identity (not persisted)
const genKey = () => (crypto?.randomUUID ? crypto.randomUUID() : String(Date.now() + Math.random()).replace(/\D/g, ''));

// Initialize a brand-new menu (creation flow). No fetch on load.
const menu = ref({
    name: 'Main Menu',
    categories: [
        { __key: genKey(), name: '', items: [
            { __key: genKey(), name: '', price: 0, available: true, description: '', primary_image_url: '', tags: [], skus: [] },
        ] },
    ],
});
const formRef = ref();
const saving = ref(false);
const itemTagsCsv = ref({});

// Catalog (global pool of menu items; MVP stores name only)
const catalog = ref([]);
const createDialogVisible = ref(false);
const draftItem = ref({ name: '' });

const openCreateDialog = () => {
    draftItem.value = { name: '' };
    createDialogVisible.value = true;
};

const createItem = () => {
    const name = (draftItem.value.name || '').trim();
    if (!name) return;
    catalog.value.push({ __key: genKey(), name });
    createDialogVisible.value = false;
};

const removeFromCatalog = (key) => {
    catalog.value = catalog.value.filter(i => i.__key !== key);
};

// Normalize structure to expected shapes (used before saving if needed)
const normalizeMenu = (raw) => {
    const out = { name: raw?.name || 'Main Menu', categories: Array.isArray(raw?.categories) ? raw.categories : [] };
    out.categories = out.categories.map(cat => ({
        __key: cat.__key || genKey(),
        name: cat.name || 'Category',
        items: Array.isArray(cat.items) ? cat.items.map(normalizeItem) : []
    }));
    return out;
};

const normalizeItem = (it) => {
    const item = {
        __key: it.__key || genKey(),
        name: it.name || 'Item',
        price: it.price ?? 0,
        description: it.description || '',
        primary_image_url: it.primary_image_url || '',
        available: it.available !== false,
        tags: Array.isArray(it.tags) ? it.tags : [],
        skus: []
    };
    // skus can be [], object {name, items}, or array
    if (Array.isArray(it.skus)) {
        item.skus = it.skus.map(normalizeGroup);
    } else if (it.skus && typeof it.skus === 'object') {
        item.skus = [normalizeGroup(it.skus)];
    }
    return item;
};

const normalizeGroup = (g) => ({
    __key: g.__key || genKey(),
    name: g.name || 'Size',
    items: Array.isArray(g.items) ? g.items.map(o => ({
        __key: o.__key || genKey(),
        name: o.name || 'Option',
        group: o.group || (g.name || 'Size'),
        price: o.price ?? 0,
        description: o.description || '',
        available: o.available !== false,
        sort_order: o.sort_order ?? 0,
    })) : []
});

// Save (debounced)
const debounce = (fn, delay = 800) => {
    let t;
    return (...args) => {
        clearTimeout(t);
        t = setTimeout(() => fn(...args), delay);
    };
};
const debouncedSave = debounce(() => {}, 800);

// Validation rules
const priceRules = [
    { required: true, message: 'Price is required', trigger: 'blur' },
    { type: 'number', message: 'Price must be a number', trigger: 'change' },
    { validator: (_, val, cb) => (val >= 0 ? cb() : cb(new Error('Price must be >= 0'))), trigger: 'change' },
];
const sortRules = [
    { type: 'number', message: 'Sort must be a number', trigger: 'change' },
    { validator: (_, val, cb) => (val >= 0 ? cb() : cb(new Error('Sort must be >= 0'))), trigger: 'change' },
];

const validateAll = async () => {
    try {
        await formRef.value.validate();
        ElMessage.success('Valid!');
    } catch (e) {
        ElMessage.error('Please fix validation errors.');
    }
};

const copyJson = async () => {
    const payload = JSON.stringify(menu.value, null, 2);
    try {
        await navigator.clipboard.writeText(payload);
        ElMessage.success('Menu JSON copied to clipboard');
    } catch (e) {
        ElMessage.error('Failed to copy JSON');
    }
};

// Actions
const addCategory = () => {
    if (!Array.isArray(menu.value.categories)) menu.value.categories = [];
    menu.value.categories.push({ __key: genKey(), name: '', available: true, items: [
        { __key: genKey(), name: '', price: 0, available: true, description: '', primary_image_url: '', tags: [], skus: [] },
    ] });
    debouncedSave();
};

const removeCategory = (index) => {
    menu.value.categories.splice(index, 1);
    debouncedSave();
};

const addItem = (category) => {
    if (!Array.isArray(category.items)) category.items = [];
    const it = { __key: genKey(), name: '', price: 0, available: true, description: '', primary_image_url: '', tags: [], skus: [] };
    category.items.push(it);
    itemTagsCsv.value[it.__key] = '';
    debouncedSave();
};

const addItemGlobal = () => {
    const idx = selectedCategory.value ?? 0;
    const category = menu.value.categories[idx];
    if (!category) return;
    addItem(category);
};

const removeItem = (category, itemIndex) => {
    const it = category.items[itemIndex];
    if (it) delete itemTagsCsv.value[it.uuid];
    category.items.splice(itemIndex, 1);
    debouncedSave();
};

const addSkuGroup = (item) => {
    if (!Array.isArray(item.skus)) item.skus = [];
    item.skus.push({ __key: genKey(), name: 'Size', items: [] });
    debouncedSave();
};

const removeSkuGroup = (item, groupIndex) => {
    item.skus.splice(groupIndex, 1);
    debouncedSave();
};

const addSkuOption = (group) => {
    if (!Array.isArray(group.items)) group.items = [];
    group.items.push({ __key: genKey(), name: 'Small', group: group.name || 'Size', price: 0, description: '', available: true, sort_order: group.items.length });
    debouncedSave();
};

const removeSkuOption = (group, optIndex) => {
    group.items.splice(optIndex, 1);
    debouncedSave();
};

const syncTags = (item) => {
    const csv = itemTagsCsv.value[item.__key] || '';
    item.tags = csv.split(',').map(t => t.trim()).filter(Boolean);
    debouncedSave();
};

// Do NOT fetch on create; menu starts blank with one category row.
onMounted(() => {});
</script>

<style scoped>
</style>


