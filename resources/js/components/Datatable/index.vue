<template>
    <div>
        <el-table
            :data="paginatedData"
            stripe
            border
            class="w-full rounded-lg"
            style="background: white"
            v-bind="$attrs"
        >
            <el-table-column
                v-for="col in columns"
                :key="col.prop"
                :prop="col.prop"
                :label="col.label"
                :sortable="col.sortable || false"
                :width="col.width"
                :align="col.align || 'left'"
            >
                <template v-if="col.slot" #[col.slot]="scope">
                    <slot :name="col.slot" v-bind="scope" />
                </template>
            </el-table-column>
        </el-table>
        <div class="flex justify-end mt-4">
            <el-pagination
                v-model:current-page="currentPage"
                :page-size="pageSize"
                :total="data.length"
                layout="prev, pager, next"
                @current-change="handlePageChange"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";

const props = defineProps({
    columns: { type: Array, required: true },
    data: { type: Array, required: true },
    pageSize: { type: Number, default: 10 },
});

const currentPage = ref(1);

const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * props.pageSize;
    return props.data.slice(start, start + props.pageSize);
});

function handlePageChange(page) {
    currentPage.value = page;
}

// Reset to first page if data changes
watch(
    () => props.data,
    () => {
        currentPage.value = 1;
    }
);
</script>
