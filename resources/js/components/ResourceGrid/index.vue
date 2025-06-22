<!-- resources/js/components/ResourceGrid.vue -->
<template>
    <div class="resource-grid">
        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center py-8">
            <el-skeleton :rows="6" animated />
        </div>

        <!-- Empty State -->
        <el-empty v-else-if="!items.length" description="No items found" />

        <!-- Grid View -->
        <div
            v-else
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-center"
        >
            <el-card
                v-for="item in items"
                :key="item.id"
                class="hover:shadow-lg transition-shadow"
                :body-style="{ padding: '1rem' }"
            >
                <slot name="header" :item="item"> </slot>
                <div class="space-y-2">
                    <slot name="content" :item="item"> </slot>
                </div>
                <slot name="actions" :item="item"> </slot>
            </el-card>
        </div>

        <!-- Pagination -->
        <div
            v-if="items.length > 0 && !loading && query.page > 1"
            class="flex justify-center mt-6"
        >
            <el-pagination
                :current-page="query.page"
                :page-size="query.per_page"
                :total="total"
                layout="prev, pager, next"
                @current-change="handlePageChange"
            />
        </div>
    </div>
</template>

<script setup>
defineProps({
    items: {
        type: Array,
        required: true,
    },
    loading: {
        type: Boolean,
        default: false,
    },
    titleField: {
        type: String,
        default: "name",
    },
    query: {
        type: Object,
        required: true,
    },
    total: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(["edit", "delete", "page-change"]);
function handlePageChange(page) {
    emit("page-change", page);
}
</script>
