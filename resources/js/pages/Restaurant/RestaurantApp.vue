<template>
    <div class="min-h-screen flex flex-col lg:flex-row bg-white">
        <!-- Restaurant Hero (Left Side) -->
        <RestaurantHero :restaurant="restaurant" />

        <!-- Right Side - Scrollable content -->
        <div
            class="w-full lg:w-1/2 lg:h-screen lg:overflow-y-auto bg-white"
            ref="scrollContainer"
        >
            <div
                class="p-4 sm:p-6 lg:p-12 space-y-8 lg:space-y-10 lg:pb-12"
                :style="{ paddingBottom: dynamicBottomPadding }"
            >
                <!-- Menu Section -->
                <MenuSection :restaurant="restaurant" ref="menuSectionRef" />
                <!-- Contact Section -->
                <ContactSection
                    :restaurant="restaurant"
                    ref="contactSectionRef"
                />
                <!-- Hours Section -->
                <HoursSection :restaurant="restaurant" ref="hoursSectionRef" />

                <!-- Reviews Section -->
                <ReviewsSection
                    :restaurant="restaurant"
                    ref="reviewsSectionRef"
                />

                <!-- Reservation Section -->
                <ReservationSection
                    :restaurant="restaurant"
                    ref="reservationSectionRef"
                />
            </div>
        </div>

        <!-- Mobile Quick Navigation -->
        <MobileQuickNav
            :restaurant="restaurant"
            :activeSection="activeSection"
            @scrollTo="scrollToSection"
            ref="mobileQuickNavRef"
        />
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import Restaurant from "@/models/Restaurant.js";
import RestaurantHero from "@/components/Restaurant/RestaurantHero.vue";
import ContactSection from "@/components/Restaurant/ContactSection.vue";
import MenuSection from "@/components/Restaurant/MenuSection.vue";
import HoursSection from "@/components/Restaurant/HoursSection.vue";
import ReviewsSection from "@/components/Restaurant/ReviewsSection.vue";
import ReservationSection from "@/components/Restaurant/ReservationSection.vue";
import MobileQuickNav from "@/components/Restaurant/MobileQuickNav.vue";
import { useScrollNavigation } from "@/composables/useScrollNavigation.js";

const props = defineProps({
    restaurantData: {
        type: Object,
        required: true,
    },
});

const hexToRgb = (hex) => {
    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result
        ? {
              r: parseInt(result[1], 16),
              g: parseInt(result[2], 16),
              b: parseInt(result[3], 16),
          }
        : { r: 0, g: 0, b: 0 };
};

const mixWithWhite = (color, alpha = 0.5) => {
    const rgb = hexToRgb(color);
    return `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, ${alpha})`;
};

const setTheme = (colors) => {
    const root = document.documentElement;
    root.style.setProperty("--el-color-primary", colors.primary_color);
    for (let i = 9; i >= 0; i--) {
        root.style.setProperty(
            `--el-color-primary-light-${i}`,
            mixWithWhite(colors.primary_color, i / 10)
        );
    }

    for (let i = 9; i >= 0; i--) {
        root.style.setProperty(
            `--el-color-primary-dark-${i}`,
            mixWithWhite(colors.secondary_color, i / 10)
        );
    }

    root.style.setProperty("--el-date-editor-width", "auto");
};
setTheme(props.restaurantData.config);

// Create Restaurant model instance
const restaurant = ref(new Restaurant(props.restaurantData));

// Template refs
const scrollContainer = ref(null);
const contactSectionRef = ref(null);
const menuSectionRef = ref(null);
const hoursSectionRef = ref(null);
const reviewsSectionRef = ref(null);
const reservationSectionRef = ref(null);
const mobileQuickNavRef = ref(null);

// Get the quick nav ref from the mobile component
const quickNavRef = computed(() => {
    return mobileQuickNavRef.value?.quickNavRef || null;
});

// Use scroll navigation composable
const {
    activeSection,
    scrollToSection: composableScrollToSection,
    setupScrollListeners,
} = useScrollNavigation(scrollContainer, quickNavRef);

// Dynamic bottom padding for mobile
const quickNavHeight = ref(0);
let resizeObserver = null;

const dynamicBottomPadding = computed(() => {
    const isMobile = typeof window !== "undefined" && window.innerWidth < 1024;
    if (isMobile && quickNavHeight.value > 0) {
        return `${quickNavHeight.value + 16}px`; // Add 16px buffer
    }
    return "48px"; // Default desktop padding (lg:pb-12)
});

const updateQuickNavHeight = () => {
    const quickNav = quickNavRef.value;
    if (quickNav) {
        const rect = quickNav.getBoundingClientRect();
        quickNavHeight.value = rect.height;
    }
};

// Setup section refs for navigation
const sectionRefs = computed(() => ({
    contact: contactSectionRef.value?.sectionRef,
    menu: menuSectionRef.value?.sectionRef,
    hours: hoursSectionRef.value?.sectionRef,
    reviews: reviewsSectionRef.value?.sectionRef,
    reservation: reservationSectionRef.value?.sectionRef,
}));

const scrollToSection = (sectionId) => {
    composableScrollToSection(sectionId, sectionRefs.value);
};

onMounted(() => {
    // Setup scroll listeners with a getter function for reactive section refs
    setupScrollListeners(() => sectionRefs.value);

    // Observe quick nav height changes
    const quickNav = quickNavRef.value;
    if (quickNav) {
        resizeObserver = new ResizeObserver(updateQuickNavHeight);
        resizeObserver.observe(quickNav);
        // Initial measurement with slight delay for DOM to fully render
        setTimeout(updateQuickNavHeight, 100);
    }
});

// Cleanup observer on unmount
const cleanup = () => {
    if (resizeObserver) {
        resizeObserver.disconnect();
    }
};

// Vue 3 cleanup
import { onUnmounted } from "vue";
onUnmounted(cleanup);
</script>

<style scoped>
.font-playfair {
    font-family: "Playfair Display", serif;
}

/* Custom scrollbar for right panel */
.lg\:overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.lg\:overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.lg\:overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.lg\:overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
