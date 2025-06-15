<template>
    <div>
        <el-table
            :data="props.data"
            stripe
            border
            class="w-full rounded-lg"
            style="background: white"
            v-bind="$attrs"
            v-loading="props.loading"
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
            </el-table-column>
            <el-table-column
                v-if="hasActions"
                prop="actions"
                label="Actions"
                width="100"
            >
                <template #default="scope">
                    <slot name="actions" v-bind="scope" />
                </template>
            </el-table-column>
        </el-table>
        <div class="flex justify-end mt-4">
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
import { ref, computed, watch, useSlots } from "vue";

const emit = defineEmits(["page-change"]);

const props = defineProps({
    columns: { type: Array, required: true },
    data: { type: Array, required: true },
    query: { type: Object, required: true },
    total: { type: Number, required: true },
    loading: { type: Boolean, default: false },
});

const hasActions = computed(() => !!useSlots().actions);

function handlePageChange(page) {
    emit("page-change", page);
}
</script>
