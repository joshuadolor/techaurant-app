<template>
    <div
        class="w-full lg:w-1/2 min-h-screen lg:h-screen flex flex-col justify-center items-center p-4 sm:p-6 lg:p-12 relative overflow-hidden"
        :style="{
            background: `linear-gradient(135deg, ${primaryColorWithAlpha(
                0.2
            )}, ${secondaryColorWithAlpha(0.05)})`,
        }"
    >
        <!-- Background decorations -->
        <div
            class="absolute top-0 right-0 w-32 h-32 lg:w-64 lg:h-64 rounded-full blur-3xl"
            :style="{ backgroundColor: primaryColorWithAlpha(0.1) }"
        ></div>
        <div
            class="absolute bottom-0 left-0 w-24 h-24 lg:w-48 lg:h-48 rounded-full blur-2xl"
            :style="{ backgroundColor: secondaryColorWithAlpha(0.08) }"
        ></div>

        <div class="relative z-10 text-center max-w-lg w-full">
            <!-- Logo -->
            <div v-if="restaurant.logoUrl" class="mb-6 lg:mb-8">
                <img
                    :src="restaurant.logoUrl"
                    :alt="restaurant.name"
                    class="w-32 h-32 lg:w-40 lg:h-40 mx-auto object-cover"
                />
            </div>

            <!-- Restaurant Name -->
            <h1
                class="text-4xl sm:text-5xl lg:text-4xl xl:text-5xl font-bold text-slate-900 font-playfair mb-3 lg:mb-4 leading-tight"
            >
                {{ restaurant.name }}
            </h1>

            <!-- Tagline -->
            <p
                v-if="restaurant.tagline"
                class="text-lg lg:text-xl text-slate-600 italic mb-6 lg:mb-6 font-light px-4"
            >
                "{{ restaurant.tagline }}"
            </p>

            <!-- Social Media Icons -->
            <div class="flex justify-center space-x-4 mb-8 lg:mb-8">
                <!-- Facebook -->
                <a
                    href="https://facebook.com/restaurant"
                    target="_blank"
                    class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-lg hover:shadow-xl cursor-pointer"
                    style="
                        background: linear-gradient(135deg, #1877f2, #42a5f5);
                    "
                >
                    <svg
                        class="w-6 h-6 text-white"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                        />
                    </svg>
                </a>

                <!-- Instagram -->
                <a
                    href="https://instagram.com/restaurant"
                    target="_blank"
                    class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-lg hover:shadow-xl cursor-pointer"
                    style="
                        background: linear-gradient(
                            135deg,
                            #e4405f,
                            #f77737,
                            #fcaf45
                        );
                    "
                >
                    <svg
                        class="w-6 h-6 text-white"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"
                        />
                    </svg>
                </a>

                <!-- YouTube -->
                <a
                    href="https://youtube.com/restaurant"
                    target="_blank"
                    class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-lg hover:shadow-xl cursor-pointer"
                    style="
                        background: linear-gradient(135deg, #ff0000, #ff4444);
                    "
                >
                    <svg
                        class="w-6 h-6 text-white"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"
                        />
                    </svg>
                </a>
            </div>

            <!-- Description -->
            <div
                v-if="restaurant.description"
                class="text-sm lg:text-base text-slate-700 leading-relaxed mb-6 lg:mb-8 px-4"
            >
                <p class="whitespace-pre-line">
                    {{ truncatedDescription }}
                </p>
                <button
                    v-if="isDescriptionTruncated"
                    @click="showDescriptionModal = true"
                    class="mt-2 text-sm font-medium transition-colors hover:underline cursor-pointer"
                    :style="{ color: restaurant.config.primaryColor }"
                >
                    Read More...
                </button>
            </div>

            <!-- Status Badge (hidden for now) -->
            <div class="mb-6 lg:mb-8 hidden">
                <span
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm border-2 shadow-sm font-medium"
                    :class="
                        restaurant.isActive ? 'text-green-800' : 'text-red-800'
                    "
                    :style="{
                        backgroundColor: restaurant.isActive
                            ? primaryColorWithAlpha(0.1)
                            : '#fef2f2',
                        borderColor: restaurant.isActive
                            ? restaurant.config.primaryColor
                            : '#fca5a5',
                    }"
                >
                    <span
                        class="w-2 h-2 rounded-full mr-2 animate-pulse"
                        :style="{
                            backgroundColor: restaurant.isActive
                                ? restaurant.config.primaryColor
                                : '#dc2626',
                        }"
                    ></span>
                    {{
                        restaurant.isActive
                            ? "Open for Service"
                            : "Currently Closed"
                    }}
                </span>
            </div>

            <!-- Today's Schedule -->
            <div
                v-if="todaySchedule"
                class="bg-white/80 backdrop-blur-sm rounded-xl p-4 lg:p-6 shadow-lg border mx-4"
                :style="{ borderColor: secondaryColorWithAlpha(0.2) }"
            >
                <h3
                    class="text-base lg:text-lg font-semibold text-slate-900 mb-2 lg:mb-3 font-playfair"
                >
                    Today's Schedule
                </h3>
                <div class="text-slate-700">
                    <p class="font-medium text-sm lg:text-base">
                        {{ today }}
                    </p>
                    <p
                        class="text-base lg:text-lg font-semibold"
                        :style="{ color: restaurant.config.primaryColor }"
                    >
                        {{ todaySchedule }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Description Modal -->
        <DescriptionModal
            v-if="showDescriptionModal"
            :restaurant="restaurant"
            :primaryColorWithAlpha="primaryColorWithAlpha"
            @close="showDescriptionModal = false"
        />
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useRestaurantColors } from "@/composables/useRestaurantColors.js";
import DescriptionModal from "./DescriptionModal.vue";

const props = defineProps({
    restaurant: {
        type: Object,
        required: true,
    },
});

// Color utilities
const { primaryColorWithAlpha, secondaryColorWithAlpha } = useRestaurantColors(
    computed(() => props.restaurant)
);

// Today's info
const today = computed(() => {
    return new Date().toLocaleDateString("en", { weekday: "long" });
});

const todaySchedule = computed(() => {
    const todayData = props.restaurant.businessHoursByDay[today.value];
    if (!todayData || todayData.data.isClosed) {
        return "Closed";
    }

    return todayData.timePeriods
        .map((period) => `${period.openTime12h} - ${period.closeTime12h}`)
        .join(", ");
});

// Description logic
const descriptionMaxLength = 250;
const showDescriptionModal = ref(false);

const isDescriptionTruncated = computed(() => {
    return props.restaurant.description.length > descriptionMaxLength;
});

const truncatedDescription = computed(() => {
    if (props.restaurant.description.length <= descriptionMaxLength) {
        return props.restaurant.description;
    }
    return (
        props.restaurant.description.substring(0, descriptionMaxLength) + "..."
    );
});
</script>

<style scoped>
.font-playfair {
    font-family: "Playfair Display", serif;
}
</style>
