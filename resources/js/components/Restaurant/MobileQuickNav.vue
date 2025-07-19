<template>
    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-50" ref="quickNavRef">
        <div
            class="bg-white/95 backdrop-blur-sm border-t border-slate-200 shadow-lg"
        >
            <div class="grid grid-cols-5 gap-1 p-2">
                <!-- Contact -->
                <button
                    @click="$emit('scrollTo', 'contact')"
                    class="flex flex-col items-center justify-center p-3 rounded-lg transition-all duration-200 hover:scale-105 group cursor-pointer"
                    :class="{ 'shadow-md': activeSection === 'contact' }"
                    :style="{
                        backgroundColor:
                            activeSection === 'contact'
                                ? primaryColorWithAlpha(0.1)
                                : 'transparent',
                        color:
                            activeSection === 'contact'
                                ? restaurant.config.primaryColor
                                : '#64748b',
                    }"
                >
                    <div
                        class="w-6 h-6 mb-1 flex items-center justify-center"
                        :class="{
                            'scale-110': activeSection === 'contact',
                        }"
                    >
                        <svg
                            class="w-5 h-5 transition-transform"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"
                            />
                        </svg>
                    </div>
                    <span
                        class="text-xs sm:text-sm font-medium truncate w-full text-center"
                        :class="{
                            'font-semibold': activeSection === 'contact',
                        }"
                    >
                        Contact
                    </span>
                </button>

                <!-- Menu -->
                <button
                    @click="$emit('scrollTo', 'menu')"
                    class="flex flex-col items-center justify-center p-3 rounded-lg transition-all duration-200 hover:scale-105 group cursor-pointer"
                    :class="{ 'shadow-md': activeSection === 'menu' }"
                    :style="{
                        backgroundColor:
                            activeSection === 'menu'
                                ? primaryColorWithAlpha(0.1)
                                : 'transparent',
                        color:
                            activeSection === 'menu'
                                ? restaurant.config.primaryColor
                                : '#64748b',
                    }"
                >
                    <div
                        class="w-6 h-6 mb-1 flex items-center justify-center"
                        :class="{ 'scale-110': activeSection === 'menu' }"
                    >
                        <svg
                            class="w-5 h-5 transition-transform"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253"
                            />
                        </svg>
                    </div>
                    <span
                        class="text-xs sm:text-sm font-medium truncate w-full text-center"
                        :class="{
                            'font-semibold': activeSection === 'menu',
                        }"
                    >
                        Menu
                    </span>
                </button>

                <!-- Hours -->
                <button
                    @click="$emit('scrollTo', 'hours')"
                    class="flex flex-col items-center justify-center p-3 rounded-lg transition-all duration-200 hover:scale-105 group cursor-pointer"
                    :class="{ 'shadow-md': activeSection === 'hours' }"
                    :style="{
                        backgroundColor:
                            activeSection === 'hours'
                                ? primaryColorWithAlpha(0.1)
                                : 'transparent',
                        color:
                            activeSection === 'hours'
                                ? restaurant.config.primaryColor
                                : '#64748b',
                    }"
                >
                    <div
                        class="w-6 h-6 mb-1 flex items-center justify-center"
                        :class="{ 'scale-110': activeSection === 'hours' }"
                    >
                        <svg
                            class="w-5 h-5 transition-transform"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <span
                        class="text-xs sm:text-sm font-medium truncate w-full text-center"
                        :class="{
                            'font-semibold': activeSection === 'hours',
                        }"
                    >
                        Hours
                    </span>
                </button>

                <!-- Reviews -->
                <button
                    @click="$emit('scrollTo', 'reviews')"
                    class="flex flex-col items-center justify-center p-3 rounded-lg transition-all duration-200 hover:scale-105 group cursor-pointer"
                    :class="{
                        'shadow-md': activeSection === 'reviews',
                    }"
                    :style="{
                        backgroundColor:
                            activeSection === 'reviews'
                                ? primaryColorWithAlpha(0.1)
                                : 'transparent',
                        color:
                            activeSection === 'reviews'
                                ? restaurant.config.primaryColor
                                : '#64748b',
                    }"
                >
                    <div
                        class="w-6 h-6 mb-1 flex items-center justify-center"
                        :class="{
                            'scale-110': activeSection === 'reviews',
                        }"
                    >
                        <svg
                            class="w-5 h-5 transition-transform"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                            />
                        </svg>
                    </div>
                    <span
                        class="text-xs sm:text-sm font-medium truncate w-full text-center"
                        :class="{
                            'font-semibold': activeSection === 'reviews',
                        }"
                    >
                        Reviews
                    </span>
                </button>

                <!-- Reservation -->
                <button
                    @click="$emit('scrollTo', 'reservation')"
                    class="flex flex-col items-center justify-center p-3 rounded-lg transition-all duration-200 hover:scale-105 group cursor-pointer"
                    :class="{
                        'shadow-md': activeSection === 'reservation',
                    }"
                    :style="{
                        backgroundColor:
                            activeSection === 'reservation'
                                ? primaryColorWithAlpha(0.1)
                                : 'transparent',
                        color:
                            activeSection === 'reservation'
                                ? restaurant.config.primaryColor
                                : '#64748b',
                    }"
                >
                    <div
                        class="w-6 h-6 mb-1 flex items-center justify-center"
                        :class="{
                            'scale-110': activeSection === 'reservation',
                        }"
                    >
                        <svg
                            class="w-5 h-5 transition-transform"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                    </div>
                    <span
                        class="text-xs sm:text-sm font-medium truncate w-full text-center"
                        :class="{
                            'font-semibold': activeSection === 'reservation',
                        }"
                    >
                        Reserve
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRestaurantColors } from "@/composables/useRestaurantColors.js";

const props = defineProps({
    restaurant: {
        type: Object,
        required: true,
    },
    activeSection: {
        type: String,
        required: true,
    },
});

defineEmits(["scrollTo"]);

const quickNavRef = ref(null);

defineExpose({
    quickNavRef,
});

// Color utilities
const { primaryColorWithAlpha } = useRestaurantColors(() => props.restaurant);
</script>

<style scoped>
/* Mobile navigation animation */
@media (max-width: 1023px) {
    .fixed.bottom-0 {
        animation: slideUp 0.3s ease-out;
    }
}

@keyframes slideUp {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>
