<template>
    <AuthenticatedLayout>
        <div>
            <div
                class="flex justify-between sm:items-center mb-4 flex-col sm:flex-row gap-4"
            >
                <PageTitle>Menus</PageTitle>

                <!-- View Toggle -->
                <div class="flex items-center gap-4 justify-between">
                    <div class="hidden">
                        <el-radio-group v-model="viewMode" size="small">
                            <el-radio-button label="table">
                                <el-icon><Grid /></el-icon>
                            </el-radio-button>
                            <el-radio-button label="grid">
                                <el-icon><Menu /></el-icon>
                            </el-radio-button>
                        </el-radio-group>
                    </div>

                    <el-button type="primary" @click="createMenu">
                        Create your Menu
                    </el-button>
                </div>
            </div>
            <Datatable
                v-if="viewMode === 'table'"
                :columns="columns"
                :data="menus"
                :query="query"
                :total="total"
                @page-change="handlePageChange"
                :loading="loading"
            >
            </Datatable>

            <!-- Grid View -->
            <ResourceGrid
                v-else
                :items="menus"
                :loading="loading"
                @edit="editMenu"
                @delete="deleteMenu"
                :query="query"
                :total="total"
                @page-change="handlePageChange"
            >
                <!-- Custom Content -->
                <template #content="{ item }">
                    <Card :item="item" />
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
import { ElMessageBox, ElMessage } from "element-plus";
import Card from "./Card.vue";
import { Grid, Menu } from "@element-plus/icons-vue";
import PageTitle from "@/components/PageTitle";
const viewMode = ref("grid");
import MenuModel from "@/models/Menu";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";

const {
    items: menus,
    total,
    loading,
    error,
    query,
    fetchAll,
} = useResourceCrudTable("menus", MenuModel);

const router = useRouter();
const auth = useAuthStore();

const handlePageChange = (page) => {
    query.page = page;
};

const columns = ref([
    { prop: "name", label: "Name", sortable: true },
    { prop: "is_active", label: "Is Active", sortable: true },
    { prop: "is_verified", label: "Is Verified", sortable: true },
    { prop: "created_at", label: "Created At", sortable: true },
]);

onMounted(() => {
    fetchAll({
        sort: "created_at",
        order: "desc",
    });
});

const createMenu = () => {
    // If user has a current restaurant id on the user model, prefer that
    const restaurantId = auth.getUser?.restaurant_id || auth.getUser?.restaurantId || 1;
    router.push({ name: 'menus.editor', params: { restaurantId } });
};

const editMenu = (menu) => {
    // Navigate to edit page or open modal
};

const deleteMenu = async (menu) => {
    try {
        await ElMessageBox.confirm(
            "Are you sure you want to delete this menu?",
            "Warning",
            {
                confirmButtonText: "OK",
                cancelButtonText: "Cancel", 
                type: "warning",
            }
        );
        await deleteItem(menu.id);
        ElMessage.success("Menu deleted successfully");
    } catch (error) {
        if (error !== "cancel") {
            ElMessage.error("Failed to delete menu");
        }
    }
};
</script>
