<template>
    <AuthenticatedLayout>
        <div class="flex flex-col h-screen">
            <h1 class="text-2xl font-bold">Tags</h1>
            <div v-for="tag in tags" :key="tag.id">
                <el-tag>{{ tag.name }}</el-tag>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout";
import useResourceCrudTable from "@/composables/useResourceCrudTable";
import Tag from "@/models/Tag";
import { onMounted } from "vue";

const {
    items: tags,
    total,
    loading,
    error,
    query,
    fetchAll,
} = useResourceCrudTable("tags", Tag);

onMounted(async () => {
    await fetchAll();
});
</script>
