<template>
    <el-switch
        v-bind="$attrs"
        :style="{
            '--el-switch-on-color': activeColor,
            '--el-switch-off-color': inactiveColor,
        }"
    >
        <template v-for="(_, slot) in $slots" #[slot]="scope">
            <slot :name="slot" v-bind="scope" />
        </template>
    </el-switch>
</template>

<script setup>
const props = defineProps({
    activeColor: {
        type: String,
        default: "#ff8904",
    },
    inactiveColor: {
        type: String,
        default: "#E5E7EB",
    },
});
</script>
