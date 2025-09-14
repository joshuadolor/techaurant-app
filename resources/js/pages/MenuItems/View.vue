<template>
    <AuthenticatedLayout>
        <div v-if="loading && !menuItem">
            <el-skeleton :rows="10" animated />
        </div>
        <div v-else-if="loadError">
            <el-alert :title="loadError" type="error" show-icon :closable="false" />
        </div>
        <div v-else>
            <div class="p-4">
                <PageTitle title="Edit Menu Item" />
                <MenuItemWizard v-if="initial" :initial="initial" @submit="handleUpdate" />
            </div>
        </div>
    </AuthenticatedLayout>
    
</template>

<script setup>
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout";
import PageTitle from "@/components/PageTitle";
import MenuItemWizard from "@/components/MenuItem/Forms/Wizard.vue";
import { useRoute, useRouter } from "vue-router";
import { onMounted, ref, computed } from "vue";
import useResourceMethod from "@/composables/useResourceMethod";

const route = useRoute();
const router = useRouter();
const { id } = route.params;

const { item: menuItem, loading, error: loadError, execute: fetchItem } = useResourceMethod("menu-items", {
    method: "get",
});

const { loading: saving, execute: updateItem } = useResourceMethod("menu-items", {
    method: "update",
});

const initial = computed(() => {
    const it = menuItem?.value;
    if (!it) return null;
    return {
        name: it.name || "",
        description: it.description || "",
        price: it.price ?? null,
        preparation_time: it.preparation_time ?? null,
        is_active: it.is_active !== false,
        is_available: it.is_available !== false,
        primary_image_url: it.primary_image_url || "",
        primary_image_file: null,
        skus: normalizeSkus(it.skus || []),
    };
});

const normalizeSkus = (apiSkus) => {
    if (!Array.isArray(apiSkus)) return [];
    const groups = new Map();
    apiSkus.forEach((s) => {
        const groupName = s.name || s.group || s.group_name || "Options";
        const value = s.value || s.option || s.description || "";
        const price = s.price != null ? Number(s.price) : null;
        const description = s.description && s.description !== value ? s.description : "";
        if (!groups.has(groupName)) groups.set(groupName, { name: groupName, items: [] });
        groups.get(groupName).items.push({ value, price, description });
    });
    return Array.from(groups.values());
};

const trimString = (val) => (typeof val === 'string' ? val.trim() : val);
const sanitizePayload = (raw) => {
    const base = {
        name: trimString(raw.name) || "",
        description: trimString(raw.description) || "",
        price: raw.price != null && raw.price !== '' ? Number(raw.price) : null,
        preparation_time: raw.preparation_time != null && raw.preparation_time !== '' ? Number(raw.preparation_time) : null,
        is_active: raw.is_active !== false,
        is_available: raw.is_available !== false,
        primary_image_url: trimString(raw.primary_image_url) || "",
    };
    const groups = Array.isArray(raw.skus) ? raw.skus.map((g) => ({
        name: trimString(g.name) || '',
        items: Array.isArray(g.items) ? g.items.map((o) => ({
            value: trimString(o.value) || '',
            price: o.price != null && o.price !== '' ? Number(o.price) : null,
            description: trimString(o.description) || '',
        })) : [],
    })) : [];
    const filteredGroups = groups.map((g) => ({
        ...g,
        items: g.items.filter((o) => o.value && o.price != null && !Number.isNaN(o.price)),
    })).filter((g) => g.name && g.items.length > 0);
    return { ...base, skus: filteredGroups };
};

const handleUpdate = async (data) => {
    const payload = sanitizePayload(data);
    if (data.primary_image_file instanceof File) {
        const formData = new FormData();
        // append scalars (booleans as 1/0)
        formData.append('name', payload.name);
        formData.append('description', payload.description);
        if (payload.price !== null) formData.append('price', payload.price);
        if (payload.preparation_time !== null) formData.append('preparation_time', payload.preparation_time);
        formData.append('is_active', payload.is_active ? 1 : 0);
        formData.append('is_available', payload.is_available ? 1 : 0);
        // nested arrays with bracket notation
        const appendFormData = (fd, value, keyPrefix) => {
            if (value === null || value === undefined || value === "") return;
            if (Array.isArray(value)) {
                value.forEach((v, i) => appendFormData(fd, v, `${keyPrefix}[${i}]`));
            } else if (typeof value === 'object' && !(value instanceof File) && !(value instanceof Blob)) {
                Object.entries(value).forEach(([k, v]) => appendFormData(fd, v, keyPrefix ? `${keyPrefix}[${k}]` : k));
            } else {
                fd.append(keyPrefix, value);
            }
        };
        appendFormData(formData, payload.skus, 'skus');
        formData.append('primary_image_url', data.primary_image_file);
        await updateItem(id, formData, { headers: { 'Content-Type': 'multipart/form-data', 'Accept': 'application/json' } }, { isFormData: true, method: 'post' });
    } else {
        delete payload.primary_image_url;
        console.log(payload);
        await updateItem(id, payload);
    }
    router.replace({ name: 'menu-items' });
};

onMounted(async () => {
    await fetchItem(id);
});
</script>

<style scoped>
</style>
