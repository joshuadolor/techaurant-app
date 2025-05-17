<template>
    <LoadingAuth />
    <router-view></router-view>
</template>

<script setup>
import LoadingAuth from "@/components/Loader/Auth";
import { notify } from "@/utils/notification";
import { useRouter, useRoute } from "vue-router";
import { onMounted } from "vue";

onMounted(() => {
    const route = useRoute();
    const router = useRouter();

    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get("msg");

    if (message) {
        notify.success(message);
        router.replace({ path: route.path });
    }
});
</script>
