<template>
    <AuthenticatedLayout>
        <div v-if="(loading && !item) || !item">
            <el-skeleton :rows="10" animated />
        </div>
        <div v-else-if="error">
            <el-alert :title="error" type="error" show-icon :closable="false" />
        </div>
        <div v-else>
            <!-- Header with restaurant name and actions -->
            <div class="restaurant-header">
                <div
                    class="flex flex-col justify-center sm:flex-row items-center gap-4"
                >
                    <LogoUploader
                        v-model:logo="restaurantLogo"
                        v-model:logoPreview="restaurantLogoPreview"
                        uploadText="Click to update logo"
                        uploadHint="PNG, JPG up to 2MB"
                        :altText="item.name"
                        class="restaurant-logo"
                        @change="handleLogoChange"
                    />
                    <div class="flex flex-col gap-2">
                        <PageTitle class="text-orange-400 text-3xl">
                            {{ item.name }}
                        </PageTitle>
                        <p class="text-gray-600">
                            {{ item?.tagline || "No tagline set" }}
                        </p>
                        <p class="text-gray-500 text-sm italic">
                            created at {{ item.createdAt }}
                        </p>
                        <div class="flex gap-2">
                            <el-button type="primary" @click="openWebsite">
                                Open Website
                            </el-button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <el-tabs v-model="activeTab" class="restaurant-tabs">
                <!-- Info Tab -->
                <el-tab-pane label="Restaurant Information" name="info">
                    <div class="tab-content">
                        <RestaurantInfoTab
                            v-if="activeTab === 'info'"
                            :restaurant="item"
                            @edit="editTab('info')"
                        />
                    </div>
                </el-tab-pane>

                <!-- Contact Tab -->
                <el-tab-pane label="Contact Information" name="contact">
                    <div class="tab-content">
                        <RestaurantContactTab
                            v-if="activeTab === 'contact'"
                            :data="item?.contact"
                            @edit="editTab('contact')"
                        />
                    </div>
                </el-tab-pane>

                <!-- Business Hours Tab -->
                <el-tab-pane label="Business Hours" name="hours">
                    <div class="tab-content">
                        <RestaurantHoursTab
                            v-if="activeTab === 'hours'"
                            :data="item?.businessHoursByDay"
                            @edit="editTab('hours')"
                        />
                    </div>
                </el-tab-pane>

                <!-- Settings Tab -->
                <el-tab-pane label="Settings" name="settings">
                    <div class="tab-content">
                        <RestaurantSettingsTab
                            v-if="activeTab === 'settings'"
                            :data="item?.config"
                            @edit="editTab('settings')"
                        />
                    </div>
                </el-tab-pane>
            </el-tabs>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/layouts/AuthenticatedLayout";
import PageTitle from "@/components/PageTitle";
import { useRoute, useRouter } from "vue-router";
import { onMounted, ref, provide, watch } from "vue";
import { notify } from "@/utils/notification";
import LogoUploader from "@/components/FormElements/LogoUploader/index.vue";
import Restaurant from "@/models/Restaurant";
import useResourceMethod from "@/composables/useResourceMethod";

// Import tab components (now using form-based edit mode)
import RestaurantInfoTab from "./tabs/RestaurantInfoTab.vue";
import RestaurantContactTab from "./tabs/RestaurantContactTab.vue";
import RestaurantHoursTab from "./tabs/RestaurantHoursTab.vue";
import RestaurantSettingsTab from "./tabs/RestaurantSettingsTab.vue";

const route = useRoute();
const router = useRouter();
const { id } = route.params;

const activeTab = ref("info");

// Reactive properties for logo
const restaurantLogo = ref(null);
const restaurantLogoPreview = ref("");

const { item, loading, error, execute } = useResourceMethod("restaurants", {
    method: "get",
    model: Restaurant,
});

const {
    loading: updateLogoLoading,
    error: updateLogoError,
    execute: updateLogo,
} = useResourceMethod("restaurants", {
    method: "update",
});

// Watch for item changes to update logo data
watch(
    item,
    (newItem) => {
        if (newItem) {
            restaurantLogo.value = newItem.logoUrl;
            restaurantLogoPreview.value = newItem.logoUrl;
        }
    },
    { immediate: true }
);

const handleLogoChange = async ({ file, preview }) => {
    notify.info({
        message: "Updating logo...",
        position: "bottom-right",
    });
    const formData = new FormData();
    formData.append("logo", file);
    formData.append("_method", "PUT");
    await updateLogo(
        id,
        formData,
        {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        },
        { isFormData: true, method: "post" }
    );
    notify.success({
        message: "Logo updated!",
        position: "bottom-right",
    });
};

const editTab = (tabName) => {
    router.push(`/restaurants/${id}/edit?tab=${tabName}`);
};

const openWebsite = () => {
    window.open(item.value.websiteUrl, "_blank");
};

provide("refreshData", async () => {
    notify.info({
        message: "Refreshing restaurant data...",
        position: "bottom-right",
    });
    await execute(id);
    notify.success(
        { message: "Restaurant updated!", position: "bottom-right" },
        true
    );
});

onMounted(async () => {
    await execute(id);
});
</script>

<style scoped>
.restaurant-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
}

.restaurant-tabs {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
}

.restaurant-tabs :deep(.el-tabs__header) {
    padding: 0.5rem 1rem;
    margin-bottom: 0;
}

.restaurant-tabs :deep(.el-tabs__content) {
    padding: 0;
}

.restaurant-tabs :deep(.el-tab-pane) {
    padding: 0;
}

.tab-content {
    background: #ffffff;
    border-radius: 8px;
    padding: 24px;
}

.restaurant-logo {
    width: 128px;
    height: 128px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}
</style>
