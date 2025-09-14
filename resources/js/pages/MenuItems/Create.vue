<template>
    <AuthenticatedLayout>
        <div class="p-4">
            <PageTitle title="Create Menu Item" />
            <MenuItemWizard @submit="handleSubmit" />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout";
import PageTitle from "@/components/PageTitle";
import MenuItemWizard from "@/components/MenuItem/Forms/Wizard.vue";
import useResourceMethod from "@/composables/useResourceMethod";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";

const authStore = useAuthStore();
const router = useRouter();

const { loading, execute: createMenuItem } = useResourceMethod(
    "menu-items",
    {
        method: "create",
    }
);

const trimString = (val) => (typeof val === 'string' ? val.trim() : val);

const sanitizeMenuItemPayload = (raw) => {
    // Base fields
    const base = {
        name: trimString(raw.name) || "",
        description: trimString(raw.description) || "",
        price: raw.price != null && raw.price !== '' ? Number(raw.price) : null,
        preparation_time: raw.preparation_time != null && raw.preparation_time !== '' ? Number(raw.preparation_time) : null,
        is_active: raw.is_active !== false,
        is_available: raw.is_available !== false,
        primary_image_url: trimString(raw.primary_image_url) || "",
    };

    // Sanitize variant groups → keep groups with a non-empty name or with valid options
    const groups = Array.isArray(raw.skus) ? raw.skus.map((g) => ({
        name: trimString(g.name) || '',
        items: Array.isArray(g.items) ? g.items.map((o) => ({
            value: trimString(o.value) || '',
            price: o.price != null && o.price !== '' ? Number(o.price) : null,
            description: trimString(o.description) || '',
        })) : [],
    })) : [];

    // Filter out empty options
    const filteredGroups = groups.map((g) => ({
        ...g,
        items: g.items.filter((o) => o.value && o.price != null && !Number.isNaN(o.price)),
    })).filter((g) => g.name && g.items.length > 0);

    return {
        ...base,
        skus: filteredGroups,
    };
};

const handleSubmit = async (data) => {
    const payload = sanitizeMenuItemPayload(data);

    // If an image file exists, send as multipart/form-data for binary upload
    if (data.primary_image_file instanceof File) {
        const formData = new FormData();

        // Helper: append nested data using PHP-style bracket notation so Laravel sees arrays
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

        // Scalars (convert booleans to 1/0 for stricter validators)
        appendFormData(formData, {
            name: payload.name,
            description: payload.description,
            price: payload.price,
            preparation_time: payload.preparation_time,
            is_active: payload.is_active ? 1 : 0,
            is_available: payload.is_available ? 1 : 0,
        });

        // Nested SKUs as arrays
        appendFormData(formData, payload.skus, 'skus');

        // Binary image
        formData.append('primary_image_url', data.primary_image_file);

        await createMenuItem(formData, { headers: { 'Content-Type': 'multipart/form-data', 'Accept': 'application/json' } });
    } else {
        await createMenuItem(payload);
    }
    router.replace({ name: "menu-items" });
};
</script>
