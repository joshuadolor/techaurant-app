<template>
    <AuthenticatedLayout>
        <div class="p-4">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">User Management</h1>
                <el-button type="primary" @click="handleAddUser"
                    >Add User</el-button
                >
            </div>
            <Datatable :columns="columns" :data="users">
                <template #actions="scope">
                    <el-button type="primary" @click="editUser(scope.row)"
                        >Edit</el-button
                    >
                    <el-button type="danger" @click="deleteUser(scope.row)"
                        >Delete</el-button
                    >
                    <el-button type="primary" @click="viewUser(scope.row)"
                        >View</el-button
                    >
                </template>
            </Datatable>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import Datatable from "@/components/Datatable";
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout";

const columns = ref([
    { prop: "name", label: "Name", sortable: true },
    { prop: "email", label: "Email", sortable: true },
    { prop: "role", label: "Role", sortable: true },
    { prop: "created_at", label: "Created At", sortable: true },
    {
        prop: "actions",
        label: "Actions",
        slot: "actions",
        width: 140,
        align: "center",
    },
]);

// Example data, replace with API call
const users = ref([
    {
        name: "John Doe",
        email: "john@example.com",
        role: "Admin",
        created_at: "2024-06-01",
    },
    {
        name: "Jane Smith",
        email: "jane@example.com",
        role: "Manager",
        created_at: "2024-05-15",
    },
    {
        name: "Michael Johnson",
        email: "michael@example.com",
        role: "User",
        created_at: "2024-05-10",
    },
    {
        name: "Sarah Williams",
        email: "sarah@example.com",
        role: "Manager",
        created_at: "2024-05-01",
    },
    {
        name: "Robert Brown",
        email: "robert@example.com",
        role: "User",
        created_at: "2024-04-28",
    },
    {
        name: "Emily Davis",
        email: "emily@example.com",
        role: "Admin",
        created_at: "2024-04-15",
    },
    {
        name: "James Wilson",
        email: "james@example.com",
        role: "User",
        created_at: "2024-04-10",
    },
    {
        name: "Lisa Anderson",
        email: "lisa@example.com",
        role: "Manager",
        created_at: "2024-04-01",
    },
    {
        name: "David Martinez",
        email: "david@example.com",
        role: "User",
        created_at: "2024-03-25",
    },
    {
        name: "Jennifer Taylor",
        email: "jennifer@example.com",
        role: "User",
        created_at: "2024-03-15",
    },
    {
        name: "William Thomas",
        email: "william@example.com",
        role: "Manager",
        created_at: "2024-03-10",
    },
    {
        name: "Michelle Garcia",
        email: "michelle@example.com",
        role: "User",
        created_at: "2024-03-01",
    },
    // ...more users
]);

const handleAddUser = () => {
    // Open add user modal or navigate to add user page
};

const editUser = (row) => alert("Edit " + row.name);
const deleteUser = (row) => alert("Delete " + row.name);
const viewUser = (row) => alert("View " + row.name);
</script>
