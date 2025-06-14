<template>
    <AuthenticatedLayout>
        <div>
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Restaurants</h1>
            </div>
            <Datatable
                :columns="columns"
                :data="restaurants"
                :query="query"
                :total="total"
                @page-change="handlePageChange"
            >
            </Datatable>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout";
import Datatable from "@/components/Datatable";
import useResourceCrudTable from "@/composables/useResourceCrudTable";

const {
    items: restaurants,
    total,
    loading,
    error,
    query,
    fetchAll,
} = useResourceCrudTable("restaurants");

const handlePageChange = (page) => {
    query.page = page;
};

const columns = ref([
    { prop: "name", label: "Name", sortable: true },
    { prop: "address", label: "Address", sortable: true },
    { prop: "phone", label: "Phone", sortable: true },
    { prop: "email", label: "Email", sortable: true },
    { prop: "status", label: "Status", sortable: true },
    { prop: "created_at", label: "Created At", sortable: true },
]);

onMounted(() => {
    fetchAll({
        sort: "created_at",
        order: "desc",
    });
});
</script>
