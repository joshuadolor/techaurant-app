<template>
    <AuthenticatedLayout>
        <div>
            <MenuItemCreate
                :model-value="menuItem"
                :submitAction="handleSubmit"
            />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout";
import PageTitle from "@/components/PageTitle";
import MenuItemCreate from "@/components/MenuItem/Forms/Create";
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

const menuItem = ref({
    name: "",
    slug: "",
    description: "",
    price: null,
    preparation_time: null,
    is_active: true,
    is_available: true,
    primary_image_url: "",
});

const handleSubmit = async (data) => {
    const created = await createMenuItem(data);
    router.replace({
        name: "menu-items",
        params: {
            id: created.uuid,
        },
    });
};
</script>
