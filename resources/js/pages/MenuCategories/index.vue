<template>
    <AuthenticatedLayout>
        <div>
            <div class="flex justify-between sm:items-center mb-4 flex-col sm:flex-row gap-4">
                <PageTitle>Menu Categories</PageTitle>

                <div class="flex items-center gap-4 justify-between">
                    <el-button type="primary" @click="createCategory">Create Category</el-button>
                </div>
            </div>

            <Datatable
                v-if="viewMode === 'table'"
                :columns="columns"
                :data="categories"
                :query="query"
                :total="total"
                @page-change="handlePageChange"
                :loading="loading"
            />

            <ResourceGrid
                v-else
                :items="categories"
                :loading="loading"
                :query="query"
                :total="total"
                @page-change="handlePageChange"
            >
                <template #content="{ item }">
                    <el-card>
                        <div class="space-y-1">
                            <h3 class="text-lg font-semibold">{{ item.name }}</h3>
                            <p class="text-gray-500 text-sm">{{ item.description || 'No description' }}</p>
                        </div>
                    </el-card>
                </template>
            </ResourceGrid>
        </div>
    </AuthenticatedLayout>
    
</template>

<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout";
import Datatable from "@/components/Datatable";
import useResourceCrudTable from "@/composables/useResourceCrudTable";
import ResourceGrid from "@/components/ResourceGrid";
import PageTitle from "@/components/PageTitle";
import { useRouter } from "vue-router";

const viewMode = ref("grid");

const { items: categories, total, loading, error, query, fetchAll } =
    useResourceCrudTable("menu-categories");

const router = useRouter();

const handlePageChange = (page) => {
    query.page = page;
};

const columns = ref([
    { prop: "name", label: "Name", sortable: true },
    { prop: "is_active", label: "Active", sortable: true },
]);

onMounted(() => {
    fetchAll({ sort: "created_at", order: "desc" });
});

const createCategory = () => {
    router.push({ name: "menu-categories.create" });
};
</script>


