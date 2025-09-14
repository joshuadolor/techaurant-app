<template>
    <AuthenticatedLayout>
        <div>
            <MenuCategoryCreate
                :model-value="category"
                :submitAction="handleSubmit"
            />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout";
import MenuCategoryCreate from "@/components/MenuCategory/Forms/Create";
import useResourceMethod from "@/composables/useResourceMethod";
import { useRouter } from "vue-router";

const router = useRouter();

const { loading, execute: createCategory } = useResourceMethod(
    "menu-categories",
    {
        method: "create",
    }
);

const category = ref({
    name: "",
    description: "",
    is_active: true,
    is_available: true,
    primary_image_url: "",
});

const handleSubmit = async (data) => {
    await createCategory(data);
    router.replace({ name: "menu-categories" });
};
</script>


