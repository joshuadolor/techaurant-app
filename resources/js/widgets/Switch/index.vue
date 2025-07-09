<template>
    <el-switch
        v-bind="$attrs"
        :active-color="activeColor"
        :inactive-color="inactiveColor"
        v-on="$listeners"
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
        default: "#FF7A1A",
    },
    inactiveColor: {
        type: String,
        default: "#E5E7EB",
    },
});
</script>
