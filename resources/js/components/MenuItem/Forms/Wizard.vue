<template>
    <div class="menu-item-wizard max-w-3xl mx-auto pb-28 sm:pb-0">
        <el-steps :active="step" align-center finish-status="success" class="mb-6">
            <el-step title="Basic" />
            <el-step title="Pricing & Variants" />
            <el-step title="Review" />
        </el-steps>

        <el-form ref="formRef" :model="form" :rules="rules" label-position="top">
            <div v-show="step === 0" class="space-y-4">
                <div class="flex flex-col lg:flex-row lg:items-start gap-4 items-center">
                    <div class="w-full md:w-5/12 ">
                        <el-form-item label="">
                            <LogoUploader
                                v-model:logo="form.primary_image_file"
                                v-model:logoPreview="form.primary_image_url"
                                uploadText="Tap to upload image"
                                uploadHint="PNG, JPG up to 2MB"
                                cropperTitle="Crop Item Image"
                                imageType="Primary Image"
                            />
                        </el-form-item>
                    </div>
                    <div class="w-full md:flex-1 min-w-0 space-y-4">
                        <el-form-item label="Item Name" prop="name">
                            <el-input v-model="form.name" placeholder="e.g., Classic Burger" />
                        </el-form-item>
                        <el-form-item label="Description" prop="description">
                            <el-input v-model="form.description" type="textarea" :rows="4" placeholder="Short description" />
                        </el-form-item>
                    </div>
                </div>
            </div>

            <div v-show="step === 1" class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:flex-nowrap gap-2 sm:gap-4 items-start">
                    <div class="w-full sm:flex-1" v-if="!hasSkus">
                        <el-form-item label="Price" prop="price">
                            <el-input v-model.number="form.price" type="number" :min="0" :step="0.01" placeholder="0.00" />
                        </el-form-item>
                    </div>
                    <div class="w-full sm:flex-1">
                        <el-form-item label="Preparation Time (minutes)" prop="preparation_time">
                            <el-input v-model.number="form.preparation_time" type="number" :min="0" :step="1" placeholder="e.g., 10" />
                        </el-form-item>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="font-medium">Variants</span>
                            <el-tooltip placement="top" popper-class="max-w-300" :content="'Use variants when an item has options like Size. Example: -> Item is Soda; variants could be 1 Liter, Can, etc. or -> Item is Pizza; variants could be Small, Medium, Large, etc.'  ">
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full border bg-gray-50 text-gray-600 text-[11px] cursor-help">i</span>
                            </el-tooltip>
                        </div>
                        <el-button  @click="addSku" v-if="hasSkus">Add Variant</el-button>
                    </div>
                    <div v-if="form.skus.length" class="space-y-3" ref="skuListRef">
                        <el-card v-for="(group, gIdx) in form.skus" :key="group.__key" class="relative" body-style="{padding: '12px'}">
                            <div class="flex items-center gap-3 mb-3">
                                <el-form-item :prop="`skus.${gIdx}.name`" :error="skuGroupErrors[gIdx] || ''" :rules="[{ required: true, message: 'Group name is required', trigger: 'blur' }]" label="Group Name" class="m-0 grow">
                                    <el-input v-model="group.name" placeholder="e.g., Size" />
                                </el-form-item>
                                <el-button   @click="addSkuOption(gIdx)">Add Option</el-button>
                                <el-button class="absolute right-0 top-0" size="small" type="danger" @click="removeSku(gIdx)">Remove Group</el-button>
                            </div>
                            <div v-if="group.items && group.items.length" class="space-y-3">
                                <div v-for="(opt, oIdx) in group.items" :key="opt.__key" class="space-y-2">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <el-form-item :prop="`skus.${gIdx}.items.${oIdx}.value`" :rules="[{ required: true, message: 'Option value is required', trigger: 'blur' }]" label="Value">
                                            <el-input v-model="opt.value" placeholder="e.g., 12oz" />
                                        </el-form-item>
                                        <el-form-item :prop="`skus.${gIdx}.items.${oIdx}.price`" :rules="[{ required: true, message: 'Price required', trigger: 'blur' }]" label="Price">
                                            <el-input v-model.number="opt.price" type="number" :min="0" :step="0.01" placeholder="0.00" />
                                        </el-form-item>
                                        <el-form-item :prop="`skus.${gIdx}.items.${oIdx}.description`" label="Description" class="sm:col-span-2">
                                            <el-input v-model="opt.description" type="textarea" placeholder="Describe the variant" :rows="3" />
                                        </el-form-item>
                                        <div class="flex items-center justify-end sm:col-span-2">
                                            <el-button text type="danger" @click="removeSkuOption(gIdx, oIdx)">Remove Option</el-button>
                                        </div>
                                    </div>
                                    <el-divider v-if="oIdx < group.items.length - 1" class="!my-1" />
                                </div>
                            </div>
                            <div v-else class="text-sm text-gray-500">No options yet.</div>
                        </el-card>
                    </div>
                    <div v-else class="text-sm border-2 border-gray-200 rounded-md p-4  text-gray-500 flex items-center flex-col justify-center gap-4">
                        No variants added.
                        <el-button type="primary" @click="addSku">Add a Variant</el-button>
                    </div>
                </div>
            </div>

            <div v-show="step === 2" class="space-y-4">
                <el-alert type="info" :closable="false" title="Review your item before creating it" />
                <el-card body-style="{padding: '16px'}">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="w-full sm:w-36">
                            <div class="w-32 h-32 rounded overflow-hidden bg-orange-50 border border-orange-100 mx-auto sm:mx-0 flex items-center justify-center">
                                <img v-if="form.primary_image_url" :src="form.primary_image_url" alt="Preview" class="w-full h-full object-cover" />
                                <span v-else class="text-gray-400 text-xs">No Image</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-semibold text-base truncate">{{ form.name || 'Untitled Item' }}</h3>
                                <el-tag size="small" type="success" v-if="form.is_active">Active</el-tag>
                                <el-tag size="small" type="info" v-else>Inactive</el-tag>
                                <el-tag size="small" type="success" v-if="form.is_available">Available</el-tag>
                                <el-tag size="small" type="warning" v-else>Unavailable</el-tag>
                            </div>
                            <p class="text-gray-600 text-sm mt-1 whitespace-pre-line break-words">{{ form.description || '—' }}</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3 text-sm">
                                <div v-if="!hasSkus" class="p-2 rounded bg-orange-50 border border-orange-100">
                                    <div class="text-gray-500">Price</div>
                                    <div class="font-medium">{{ hasSkus ? '-' : formatMoney(form.price) }}</div>
                                </div>
                                <div class="p-2 rounded bg-orange-50 border border-orange-100">
                                    <div class="text-gray-500">Preparation Time</div>
                                    <div class="font-medium">{{ form.preparation_time != null ? form.preparation_time + ' min' : '-' }}</div>
                                </div>
                                <div class="p-2 rounded bg-orange-50 border border-orange-100">
                                    <div class="text-gray-500">Variants</div>
                                    <div class="font-medium">{{ hasSkus ? totalVariantCount + ' option' + (totalVariantCount === 1 ? '' : 's') : '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="hasSkus" class="mt-4 space-y-3">
                        <div v-for="group in form.skus" :key="group.__key" class="rounded-lg bg-orange-50 border border-orange-100">
                            <div class="px-3 py-2 font-medium text-orange-700">{{ group.name || 'Group' }}</div>
                            <div class="px-3 pb-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <div v-for="opt in (group.items || [])" :key="opt.__key" class="flex items-center justify-between rounded px-3 py-2 bg-white border border-orange-100">
                                    <div class="truncate">{{ opt.name || 'Option' }}</div>
                                    <div class="font-medium">{{ formatMoney(opt.price) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </el-card>
            </div>

            <div class="wizard-actions fixed bottom-0 left-0 right-0 z-20 bg-orange-100 p-5 flex sm:static sm:mt-6 sm:bg-transparent sm:border-0 sm:p-0 flex-col sm:flex-row sm:justify-end gap-3">
                <el-button @click="prevStep" :disabled="step === 0" class="w-full sm:w-auto">Back</el-button>
                <el-button v-if="step < 2" ref="nextBtnRef" type="primary" @click="nextStep" class="w-full sm:w-auto">Next</el-button>
                <el-button v-else ref="submitBtnRef" type="primary" :loading="submitting" @click="submit" class="w-full sm:w-auto">Create Item</el-button>
            </div>
        </el-form>
    </div>
    
</template>

<script setup>
import { ref, reactive, computed, nextTick } from 'vue';
import { ElMessage } from 'element-plus';
import Switch from '@/widgets/Switch';
import LogoUploader from '@/components/FormElements/LogoUploader/index.vue';

const props = defineProps({
    initial: { type: Object, default: () => ({}) },
});
const emit = defineEmits(['submit']);

const step = ref(0);
const submitting = ref(false);
const formRef = ref();
const nextBtnRef = ref();
const submitBtnRef = ref();
const skuListRef = ref();
const skuGroupErrors = reactive({});
const form = reactive({
    name: '',
    description: '',
    price: null,
    preparation_time: null,
    is_active: true,
    is_available: true,
    primary_image_url: '',
    primary_image_file: null,
    // skus represents groups; each group has items (options)
    skus: [],
    ...props.initial,
});

const hasSkus = computed(() => form.skus && form.skus.length > 0);
const totalVariantCount = computed(() => (form.skus || []).reduce((sum, g) => sum + ((g.items || []).length), 0));

const rules = computed(() => ({
    name: [{ required: true, message: 'Item name is required', trigger: 'blur' }],
    price: [
        { validator: (_,_val,cb) => {
            if (hasSkus.value) return cb();
            if (form.price == null || isNaN(form.price)) return cb(new Error('Price is required'));
            if (form.price < 0) return cb(new Error('Must be >= 0'));
            return cb();
        }, trigger: 'blur' },
    ],
    preparation_time: [
        { type: 'number', message: 'Must be a number', trigger: 'change' },
        { validator: (_,_val,cb) => (form.preparation_time == null || form.preparation_time >= 0 ? cb() : cb(new Error('Must be >= 0'))), trigger: 'change' },
    ],
}));

const validateFields = async (fields) => {
    if (!formRef.value) return true;
    try {
        await formRef.value.validateField(fields);
        return true;
    } catch { return false; }
};

const nextStep = async () => {
    if (step.value === 0) {
        if (!(await validateFields(['name']))) return;
    }
    if (step.value === 1) {
        if (hasSkus.value) {
            // Validate group names required
            const missingGroupName = form.skus.some((g, gIdx) => {
                const missing = !g.name || !String(g.name).trim();
                skuGroupErrors[gIdx] = missing ? 'Group name is required' : '';
                return missing;
            });
            // Validate that each named group has at least one option
            const emptyGroup = form.skus.some(g => g.name && (!g.items || g.items.length === 0));

            // Build prop list for Element Plus validation so errors show inline
            const propsToValidate = [];
            form.skus.forEach((g, gIdx) => {
                propsToValidate.push(`skus.${gIdx}.name`);
                (g.items || []).forEach((opt, oIdx) => {
                    propsToValidate.push(`skus.${gIdx}.items.${oIdx}.value`);
                    propsToValidate.push(`skus.${gIdx}.items.${oIdx}.price`);
                });
            });
            if (propsToValidate.length) {
                const ok = await validateFields(propsToValidate);
                if (!ok) return;
            }

            if (missingGroupName) return;
            if (emptyGroup) {
                // Attach inline error to the first empty named group
                const idx = form.skus.findIndex(g => g.name && (!g.items || g.items.length === 0));
                if (idx !== -1) skuGroupErrors[idx] = 'Add at least one option';
                return;
            }

            // Extra guard in case fields lack rules for some reason
            const invalid = form.skus.some(s => {
                if (!s.name || !String(s.name).trim()) return true;
                return (s.items || []).some(i => !i.value || i.price == null || i.price < 0);
            });
            if (invalid) return;
        } else {
            if (!(await validateFields(['price']))) return;
        }
    }
    step.value = Math.min(step.value + 1, 2);
};

const prevStep = () => {
    step.value = Math.max(step.value - 1, 0);
};

const submit = async () => {
    submitting.value = true;
    try {
        emit('submit', { ...form });
    } finally {
        submitting.value = false;
    }
};

const addSku = async () => {
    const isFirst = form.skus.length === 0;
    const newGroup = { __key: Math.random().toString(36).slice(2), name: '', items: [] };
    if (isFirst && form.price != null) {
        newGroup.items.push({ __key: Math.random().toString(36).slice(2), name: '', price: form.price, description: '' });
        form.price = null;
    }
    form.skus.push(newGroup);
    await nextTick();
    const root = skuListRef.value?.$el ?? skuListRef.value;
    const inputs = root?.querySelectorAll('input');
    if (inputs && inputs.length) {
        inputs[inputs.length - 1].focus();
    }
};

const removeSku = (groupIdx) => {
    form.skus.splice(groupIdx, 1);
};

const addSkuOption = async (groupIdx) => {
    const group = form.skus[groupIdx];
    if (!group.items) group.items = [];
    group.items.push({ __key: Math.random().toString(36).slice(2), value: '', price: null, description: '' });
    await nextTick();
    const root = skuListRef.value?.$el ?? skuListRef.value;
    const inputs = root?.querySelectorAll('input');
    if (inputs && inputs.length) inputs[inputs.length - 1].focus();
};

const removeSkuOption = (groupIdx, optionIdx) => {
    form.skus[groupIdx].items.splice(optionIdx, 1);
};

const formatMoney = (val) => {
    if (val == null || val === '') return '-';
    const num = Number(val);
    if (isNaN(num)) return '-';
    return `$${num.toFixed(2)}`;
};

const onEnter = (event) => {
    const tag = event?.target?.tagName;
    if (tag === 'TEXTAREA') return; // allow new lines in textarea
    const triggerClick = (refObj) => {
        const inst = refObj?.value;
        const el = inst?.$el ?? inst;
        if (el && typeof el.click === 'function') el.click();
    };
    if (step.value < 2) {
        triggerClick(nextBtnRef);
        return;
    }
    triggerClick(submitBtnRef);
};
</script>

<style scoped>
.wizard-actions :deep(.el-button + .el-button) {
    margin-left: 0 !important;
}
.tooltip-300 :deep(.el-popper) {
    max-width: 300px;
    white-space: normal;
}
</style>


