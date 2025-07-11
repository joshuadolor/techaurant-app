<template>
    <AuthenticatedLayout>
        <div>
            <div
                class="flex justify-between sm:items-center mb-4 flex-col sm:flex-row gap-4"
            >
                <PageTitle>Restaurants</PageTitle>

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

                    <el-button type="primary" @click="createRestaurant">
                        Create your Restaurant
                    </el-button>
                </div>
            </div>
            <Datatable
                v-if="viewMode === 'table'"
                :columns="columns"
                :data="restaurants"
                :query="query"
                :total="total"
                @page-change="handlePageChange"
                :loading="loading"
            >
            </Datatable>

            <!-- Grid View -->
            <ResourceGrid
                v-else
                :items="restaurants"
                :loading="loading"
                @edit="editRestaurant"
                @delete="deleteRestaurant"
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
import Restaurant from "@/models/Restaurant";
import { useRouter } from "vue-router";

const {
    items: restaurants,
    total,
    loading,
    error,
    query,
    fetchAll,
} = useResourceCrudTable("restaurants", Restaurant);

const router = useRouter();

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

const createRestaurant = () => {
    router.push({
        name: "restaurant.create",
    });
};

const editRestaurant = (restaurant) => {
    // Navigate to edit page or open modal
};

const deleteRestaurant = async (restaurant) => {
    try {
        await ElMessageBox.confirm(
            "Are you sure you want to delete this restaurant?",
            "Warning",
            {
                confirmButtonText: "OK",
                cancelButtonText: "Cancel",
                type: "warning",
            }
        );
        await deleteItem(restaurant.id);
        ElMessage.success("Restaurant deleted successfully");
    } catch (error) {
        if (error !== "cancel") {
            ElMessage.error("Failed to delete restaurant");
        }
    }
};
</script>
