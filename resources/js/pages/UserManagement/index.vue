<template>
    <AuthenticatedLayout>
        <div class="p-4">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">User Management</h1>
                <el-button type="primary" @click="handleAddUser"
                    >Add User</el-button
                >
            </div>
            <Datatable
                :columns="columns"
                :data="users"
                :query="query"
                :total="total"
                @page-change="handlePageChange"
                :loading="loading"
            >
                <template #actions="scope">
                    <el-button @click="editUser(scope.row)">Edit</el-button>
                    <el-button
                        data-testemail="{{ scope.row.email }}"
                        type="danger"
                        @click="deleteUser(scope.row)"
                        >Delete</el-button
                    >
                </template>
            </Datatable>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Datatable from "@/components/Datatable";
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout";
import useResourceCrudTable from "@/composables/useResourceCrudTable";

const {
    items: users,
    total,
    loading,
    error,
    query,
    fetchAll,
    remove: removeUser,
} = useResourceCrudTable("users");

const columns = ref([
    { prop: "name", label: "Name", sortable: true },
    { prop: "email", label: "Email", sortable: true },
    { prop: "role", label: "Role", sortable: true },
    { prop: "created_at", label: "Created At", sortable: true },
]);

const handlePageChange = (page) => {
    query.page = page;
};

onMounted(async () => {
    await fetchAll();
});

const handleAddUser = () => {
    // Open add user modal or navigate to add user page
};

const editUser = (row) => alert("Edit " + row.name);
const deleteUser = (row) => {
    removeUser(row.uuid);
};
const viewUser = (row) => alert("View " + row.name);
</script>
