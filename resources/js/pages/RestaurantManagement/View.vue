<template>
    <AuthenticatedLayout>
        <div v-if="loading">
            <el-skeleton :rows="10" animated />
        </div>
        <div v-else>
            <PageTitle>{{ item.name }}</PageTitle>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout";
import PageTitle from "@/components/PageTitle";
import { useRoute } from "vue-router";
import { onMounted } from "vue";

import useResourceCrudTable from "@/composables/useResourceCrudTable";
import Restaurant from "@/models/Restaurant";

const route = useRoute();
const { id } = route.params;

const { item, loading, error, fetchOne } = useResourceCrudTable(
    "restaurants",
    Restaurant
);

onMounted(async () => {
    await fetchOne(id);
});
</script>
