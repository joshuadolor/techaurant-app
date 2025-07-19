<template>
    <section id="contact" ref="sectionRef">
        <h2
            class="text-2xl lg:text-3xl font-bold text-slate-900 font-playfair mb-6 border-b-2 pb-2"
            :style="{ borderColor: restaurant.config.primaryColor }"
        >
            Contact Information
        </h2>
        <div class="space-y-4">
            <!-- Phone -->
            <div
                v-if="restaurant.contact.phone"
                class="flex items-start space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors"
            >
                <div
                    class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0 mt-1"
                    :style="{
                        backgroundColor: primaryColorWithAlpha(0.1),
                    }"
                >
                    <svg
                        class="w-5 h-5"
                        :style="{
                            color: restaurant.config.primaryColor,
                        }"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"
                        />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-slate-500 font-medium mb-1">Phone</p>
                    <a
                        :href="`tel:${restaurant.contact.phone}`"
                        class="text-slate-900 hover:transition-colors font-medium text-base lg:text-lg block cursor-pointer"
                        :style="{
                            '&:hover': {
                                color: restaurant.config.primaryColor,
                            },
                        }"
                    >
                        {{
                            restaurant.contact.formattedPhone ||
                            restaurant.contact.phone
                        }}
                    </a>
                </div>
            </div>

            <!-- Email -->
            <div
                v-if="restaurant.contact.email"
                class="flex items-start space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors"
            >
                <div
                    class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0 mt-1"
                    :style="{
                        backgroundColor: primaryColorWithAlpha(0.1),
                    }"
                >
                    <svg
                        class="w-5 h-5"
                        :style="{
                            color: restaurant.config.primaryColor,
                        }"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                        />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-slate-500 font-medium mb-1">Email</p>
                    <a
                        :href="`mailto:${restaurant.contact.email}`"
                        class="text-slate-900 hover:transition-colors font-medium text-base lg:text-lg block break-all cursor-pointer"
                        :style="{
                            '&:hover': {
                                color: restaurant.config.primaryColor,
                            },
                        }"
                    >
                        {{ restaurant.contact.email }}
                    </a>
                </div>
            </div>

            <!-- Address -->
            <div
                v-if="restaurant.contact.displayAddress"
                class="flex items-start space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors"
            >
                <div
                    class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0 mt-1"
                    :style="{
                        backgroundColor: primaryColorWithAlpha(0.1),
                    }"
                >
                    <svg
                        class="w-5 h-5"
                        :style="{
                            color: restaurant.config.primaryColor,
                        }"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-slate-500 font-medium mb-1">
                        Address
                    </p>
                    <p
                        class="text-slate-900 text-base lg:text-lg mb-2 leading-relaxed"
                    >
                        {{ restaurant.contact.displayAddress }}
                    </p>
                    <button
                        @click="openDirections"
                        class="text-sm cursor-pointer transition-colors hover:underline font-medium px-3 py-2 rounded-lg"
                        :style="{
                            color: restaurant.config.primaryColor,
                            backgroundColor: primaryColorWithAlpha(0.1),
                        }"
                    >
                        Get Directions →
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref } from "vue";
import { useRestaurantColors } from "@/composables/useRestaurantColors.js";

const props = defineProps({
    restaurant: {
        type: Object,
        required: true,
    },
});

const sectionRef = ref(null);

defineExpose({
    sectionRef,
});

// Color utilities
const { primaryColorWithAlpha } = useRestaurantColors(() => props.restaurant);

const openDirections = () => {
    if (props.restaurant.contact.displayAddress) {
        const encodedAddress = encodeURIComponent(
            props.restaurant.contact.displayAddress
        );
        window.open(
            `https://maps.google.com/maps?q=${encodedAddress}`,
            "_blank"
        );
    }
};
</script>

<style scoped>
.font-playfair {
    font-family: "Playfair Display", serif;
}
</style>
