<template>
    <AuthenticatedLayout>
        <div v-if="loading || !item">
            <el-skeleton :rows="10" animated />
        </div>
        <div v-else-if="error">
            <el-alert :title="error" type="error" show-icon :closable="false" />
        </div>
        <div v-else>
            <!-- Header with restaurant name and actions -->
            <div class="restaurant-header">
                <div class="flex items-center gap-4">
                    <div v-if="item.logoUrl" class="info-item full-width">
                        <img
                            :src="item.logoUrl"
                            :alt="item.name"
                            class="restaurant-logo"
                        />
                    </div>
                    <div>
                        <PageTitle>{{ item.name }}</PageTitle>
                        <p class="restaurant-description">
                            {{ item?.tagline || "No tagline set" }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <el-tabs v-model="activeTab" class="restaurant-tabs">
                <!-- Info Tab -->
                <el-tab-pane label="Restaurant Information" name="info">
                    <div class="tab-content">
                        <div class="info-grid">
                            <div class="info-item">
                                <label class="info-label">Name</label>
                                <p class="info-value">{{ item.name }}</p>
                            </div>
                            <div class="info-item">
                                <label class="info-label">Status</label>
                                <el-tag
                                    :type="
                                        item.isActive ? 'success' : 'warning'
                                    "
                                >
                                    {{ item.isActive ? "Active" : "Inactive" }}
                                </el-tag>
                            </div>
                            <div class="info-item full-width">
                                <label class="info-label">Description</label>
                                <p class="info-value">
                                    {{
                                        item.description ||
                                        "No description available"
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </el-tab-pane>

                <!-- Contact Tab -->
                <el-tab-pane label="Contact Information" name="contact">
                    <div class="tab-content">
                        <h3 class="tab-title">Contact Information</h3>
                        <div v-if="item?.contact" class="contact-grid">
                            <div class="contact-item">
                                <label class="contact-label">Email</label>
                                <p class="contact-value">
                                    <a
                                        :href="`mailto:${item.contact.email}`"
                                        class="contact-link"
                                    >
                                        {{ item.contact.email }}
                                    </a>
                                </p>
                            </div>
                            <div class="contact-item">
                                <label class="contact-label">Phone</label>
                                <p class="contact-value">
                                    <a
                                        :href="`tel:${item.contact.phone}`"
                                        class="contact-link"
                                    >
                                        {{ item.contact.phone }}
                                    </a>
                                </p>
                            </div>
                            <div
                                v-if="item.contact.website"
                                class="contact-item full-width"
                            >
                                <label class="contact-label">Website</label>
                                <p class="contact-value">
                                    <a
                                        :href="item.contact.website"
                                        target="_blank"
                                        class="contact-link"
                                    >
                                        {{ item.contact.website }}
                                    </a>
                                </p>
                            </div>
                            <div
                                v-if="item.contact.address"
                                class="contact-item full-width"
                            >
                                <label class="contact-label">Address</label>
                                <p class="contact-value">
                                    {{ item.contact.address }}
                                </p>
                            </div>
                        </div>
                        <div v-else class="empty-state">
                            <el-icon class="empty-icon"><Location /></el-icon>
                            <p>No contact information available</p>
                        </div>
                    </div>
                </el-tab-pane>

                <!-- Business Hours Tab -->
                <el-tab-pane label="Business Hours" name="hours">
                    <div class="tab-content">
                        <h3 class="tab-title">Business Hours</h3>
                        <div
                            v-if="
                                item.business_hours &&
                                item.business_hours.length > 0
                            "
                            class="hours-list"
                        >
                            <div
                                v-for="hour in item.business_hours"
                                :key="hour.id"
                                class="hour-item"
                            >
                                <div class="hour-header">
                                    <span class="hour-day">{{
                                        hour.day_of_week
                                    }}</span>
                                    <el-tag
                                        v-if="hour.is_open"
                                        type="success"
                                        size="small"
                                        >Open</el-tag
                                    >
                                    <el-tag v-else type="danger" size="small"
                                        >Closed</el-tag
                                    >
                                </div>
                                <div v-if="hour.is_open" class="hour-time">
                                    {{ formatTime(hour.open_time) }} -
                                    {{ formatTime(hour.close_time) }}
                                </div>
                                <div v-else class="hour-closed">Closed</div>
                            </div>
                        </div>
                        <div v-else class="empty-state">
                            <el-icon class="empty-icon"><Clock /></el-icon>
                            <p>No business hours configured</p>
                        </div>
                    </div>
                </el-tab-pane>

                <!-- Settings Tab -->
                <el-tab-pane label="Settings" name="settings">
                    <div class="tab-content">
                        <h3 class="tab-title">Restaurant Settings</h3>
                        <div v-if="item.config" class="settings-grid">
                            <div class="setting-item">
                                <label class="setting-label">Currency</label>
                                <p class="setting-value">
                                    {{ item.config.currency || "Not set" }}
                                </p>
                            </div>
                            <div class="setting-item">
                                <label class="setting-label">Timezone</label>
                                <p class="setting-value">
                                    {{ item.config.timezone || "Not set" }}
                                </p>
                            </div>
                            <div class="setting-item">
                                <label class="setting-label">Tax Rate</label>
                                <p class="setting-value">
                                    {{
                                        item.config.tax_rate
                                            ? `${item.config.tax_rate}%`
                                            : "Not set"
                                    }}
                                </p>
                            </div>
                            <div class="setting-item">
                                <label class="setting-label"
                                    >Delivery Fee</label
                                >
                                <p class="setting-value">
                                    {{
                                        item.config.delivery_fee
                                            ? `$${item.config.delivery_fee}`
                                            : "Not set"
                                    }}
                                </p>
                            </div>
                        </div>
                        <div v-else class="empty-state">
                            <el-icon class="empty-icon"><Setting /></el-icon>
                            <p>No settings configured</p>
                        </div>
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
import { onMounted, ref } from "vue";
import { Edit, Location, Clock, Setting } from "@element-plus/icons-vue";

import Restaurant from "@/models/Restaurant";
import useResourceMethod from "@/composables/useResourceMethod";

const route = useRoute();
const router = useRouter();
const { id } = route.params;

const activeTab = ref("info");

const { item, loading, error, execute } = useResourceMethod("restaurants", {
    method: "get",
    model: Restaurant,
});

const editRestaurant = () => {
    router.push(`/restaurants/${id}/edit`);
};

const formatTime = (time) => {
    if (!time) return "";
    const [hours, minutes] = time.split(":");
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? "PM" : "AM";
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
};

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

.restaurant-description {
    color: #6b7280;
    margin-top: 4px;
    font-size: 14px;
}

.restaurant-actions {
    display: flex;
    gap: 8px;
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

.tab-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 16px;
    color: #111827;
}

.info-grid,
.contact-grid,
.settings-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}

@media (min-width: 768px) {
    .info-grid,
    .contact-grid,
    .settings-grid {
        grid-template-columns: 1fr 1fr;
    }
}

.info-item,
.contact-item,
.setting-item {
    display: flex;
    flex-direction: column;
}

.full-width {
    grid-column: 1 / -1;
}

.info-label,
.contact-label,
.setting-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 8px;
}

.info-value,
.contact-value,
.setting-value {
    color: #111827;
    margin: 0;
}

.contact-link {
    color: #2563eb;
    text-decoration: none;
}

.contact-link:hover {
    text-decoration: underline;
}

.restaurant-logo {
    width: 128px;
    height: 128px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.empty-state {
    text-align: center;
    padding: 32px;
    color: #6b7280;
}

.empty-icon {
    font-size: 48px;
    margin-bottom: 16px;
    color: #d1d5db;
}

.hours-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.hour-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.hour-header {
    display: flex;
    align-items: center;
    gap: 12px;
}

.hour-day {
    font-weight: 500;
    color: #111827;
}

.hour-time {
    color: #6b7280;
}

.hour-closed {
    color: #9ca3af;
}
</style>
