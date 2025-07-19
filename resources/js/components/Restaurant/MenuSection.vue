<template>
    <section id="menu" ref="sectionRef">
        <h2
            class="text-2xl lg:text-3xl font-bold text-slate-900 font-playfair mb-6 border-b-2 pb-2"
            :style="{ borderColor: restaurant.config.primaryColor }"
        >
            Our Menu
        </h2>

        <!-- Menu Categories -->
        <div class="space-y-6">
            <!-- Dynamic Categories -->
            <div
                v-for="category in menuCategories"
                :key="category.key"
                class="bg-white border-2 rounded-xl overflow-hidden hover:shadow-lg transition-shadow"
                :style="{ borderColor: primaryColorWithAlpha(0.1) }"
            >
                <!-- Category Header -->
                <button
                    @click="toggleCategory(category.key)"
                    class="w-full p-4 sm:p-6 text-left hover:bg-slate-50 transition-colors"
                    :style="{
                        backgroundColor:
                            category.key === 'appetizers'
                                ? primaryColorWithAlpha(0.05)
                                : secondaryColorWithAlpha(0.05),
                    }"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <h3
                                class="text-lg font-bold text-slate-900 font-playfair"
                            >
                                {{ category.name }}
                            </h3>

                            <!-- Status Badge -->
                            <span
                                class="px-3 py-1 text-xs font-medium rounded-full"
                                :style="{
                                    backgroundColor:
                                        getCategoryStatus(category).color ===
                                        'secondary'
                                            ? props.restaurant.config
                                                  .secondaryColor ||
                                              props.restaurant.config
                                                  .primaryColor
                                            : getCategoryStatus(category).color,
                                    color: 'white',
                                }"
                            >
                                {{ getCategoryStatus(category).label }}
                            </span>
                        </div>

                        <!-- Collapse Arrow -->
                        <div
                            class="transition-transform duration-200"
                            :class="{
                                'rotate-180': expandedCategories[category.key],
                            }"
                        >
                            <svg
                                class="w-5 h-5 text-slate-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </div>
                    </div>

                    <!-- Category Description -->
                    <p
                        v-if="category.description"
                        class="text-slate-600 text-sm mt-2"
                    >
                        {{ category.description }}
                    </p>
                    <p v-else class="text-slate-400 text-sm italic mt-2">
                        Discover our {{ category.name.toLowerCase() }} selection
                    </p>
                </button>

                <!-- Collapsible Content -->
                <transition name="slide">
                    <div
                        v-show="expandedCategories[category.key]"
                        class="border-t border-slate-100"
                    >
                        <!-- Loading State for Appetizers -->
                        <div
                            v-if="category.key === 'appetizers'"
                            class="p-4 sm:p-6 space-y-4"
                        >
                            <div
                                v-for="i in 3"
                                :key="i"
                                class="flex justify-between items-start space-x-4 p-4 rounded-lg bg-slate-50"
                            >
                                <div class="flex-1">
                                    <div
                                        class="h-4 bg-slate-300 rounded animate-pulse mb-2"
                                    ></div>
                                    <div
                                        class="h-3 bg-slate-200 rounded animate-pulse w-3/4"
                                    ></div>
                                </div>
                                <div class="text-right">
                                    <div
                                        class="h-4 bg-slate-300 rounded animate-pulse w-12"
                                    ></div>
                                </div>
                            </div>
                        </div>

                        <!-- Categories with Items -->
                        <div
                            v-else-if="
                                category.items && category.items.length > 0
                            "
                            class="p-4 sm:p-6 space-y-4"
                        >
                            <div
                                v-for="item in category.items"
                                :key="item.id"
                                class="flex justify-between items-start space-x-4 p-4 rounded-lg transition-colors relative"
                                :class="{
                                    'bg-slate-50 hover:bg-slate-100':
                                        item.available,
                                    'bg-slate-50 opacity-90': !item.available,
                                }"
                            >
                                <!-- Not Available Overlay -->
                                <div
                                    v-if="!item.available"
                                    class="absolute inset-0 flex items-center justify-center bg-white/70 rounded-lg"
                                >
                                    <span
                                        class="text-slate-600 font-medium text-sm bg-white px-3 py-1 rounded-full shadow-sm border"
                                    >
                                        Not Available
                                    </span>
                                </div>

                                <div class="flex-1">
                                    <div
                                        class="flex items-center space-x-2 mb-1"
                                    >
                                        <h4
                                            class="font-semibold text-base"
                                            :class="{
                                                'text-slate-900':
                                                    item.available,
                                                'text-slate-400':
                                                    !item.available,
                                            }"
                                        >
                                            {{ item.name }}
                                        </h4>
                                    </div>

                                    <!-- Description with fallback -->
                                    <p
                                        v-if="item.description"
                                        class="text-sm leading-relaxed mb-3"
                                        :class="{
                                            'text-slate-600': item.available,
                                            'text-slate-400': !item.available,
                                        }"
                                    >
                                        {{ item.description }}
                                    </p>
                                    <p
                                        v-else
                                        class="text-slate-400 text-sm italic mb-3"
                                    >
                                        A delicious
                                        {{
                                            category.name
                                                .toLowerCase()
                                                .slice(0, -1)
                                        }}
                                        option available at our restaurant.
                                    </p>

                                    <!-- Tags -->
                                    <div
                                        v-if="item.tags && item.tags.length > 0"
                                        class="flex flex-wrap items-center gap-2"
                                    >
                                        <span
                                            v-for="tag in item.tags"
                                            :key="tag.label"
                                            class="px-2 py-1 text-xs font-medium rounded-full"
                                            :style="getTagColor(tag)"
                                            :class="{
                                                'opacity-60': !item.available,
                                            }"
                                        >
                                            {{ tag.label }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="text-right">
                                    <div
                                        class="font-bold text-lg"
                                        :class="{
                                            'text-slate-900': item.available,
                                            'text-slate-400': !item.available,
                                        }"
                                    >
                                        ${{ item.price }}
                                    </div>
                                    <div
                                        class="text-xs mt-1"
                                        :class="{
                                            'text-slate-500': item.available,
                                            'text-slate-400': !item.available,
                                        }"
                                    >
                                        {{ item.unit }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Empty Category -->
                        <div v-else class="p-4 sm:p-6">
                            <div
                                class="flex items-center justify-center py-8 text-slate-500"
                            >
                                <div class="text-center">
                                    <div
                                        class="w-12 h-12 bg-slate-200 rounded-full mx-auto mb-3 flex items-center justify-center"
                                    >
                                        <svg
                                            class="w-6 h-6"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                            />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium">
                                        {{ category.name }} will be available
                                        soon
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
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
const { primaryColorWithAlpha, secondaryColorWithAlpha } = useRestaurantColors(
    () => props.restaurant
);

// Sample menu data (in real app, this would come from props or API)
const menuCategories = ref([
    {
        key: "appetizers",
        name: "Appetizers",
        description: "", // No description to test fallback
        items: [
            // Skeleton loading items - no real data
        ],
    },
    {
        key: "mainCourses",
        name: "Main Courses",
        description: "Hearty dishes made with fresh ingredients",
        items: [
            {
                id: 1,
                name: "Sisig with Egg",
                description:
                    "Our signature crispy pork sisig served on a sizzling cast-iron plate, perfectly seasoned with onions, chilies, and calamansi. Topped with a farm-fresh egg that's cooked right at your table for the ultimate interactive dining experience. A true Filipino classic that never fails to impress!",
                price: 40,
                unit: "per serving",
                available: true,
                tags: [
                    {
                        label: "Best Seller",
                        color: "#ef4444",
                        textColor: "white",
                    },
                    { label: "Spicy", color: "#f97316", textColor: "white" },
                    {
                        label: "Gluten Free",
                        color: "primary",
                        textColor: "primary",
                    },
                ],
            },
            {
                id: 2,
                name: "Sinigang na Baboy",
                description:
                    "A heartwarming Filipino comfort soup featuring tender pork ribs slow-cooked in our house-made tamarind broth. Loaded with fresh vegetables including kangkong, radish, tomatoes, and long beans. Served with steamed jasmine rice and our homemade fish sauce on the side.",
                price: 20,
                unit: "per bowl",
                available: false, // Not available to test unavailable state
                tags: [
                    {
                        label: "Comfort Food",
                        color: "secondary",
                        textColor: "secondary",
                    },
                    { label: "Healthy", color: "#10b981", textColor: "white" },
                    {
                        label: "Gluten Free",
                        color: "primary",
                        textColor: "primary",
                    },
                ],
            },
            {
                id: 3,
                name: "Fresh Vegetable Lumpia",
                description: "", // No description to test fallback
                price: 15,
                unit: "3 pieces",
                available: true,
                tags: [
                    { label: "Vegan", color: "#16a34a", textColor: "white" },
                    { label: "Healthy", color: "#10b981", textColor: "white" },
                    {
                        label: "Gluten Free",
                        color: "primary",
                        textColor: "primary",
                    },
                ],
            },
            {
                id: 4,
                name: "Grilled Bangus Belly",
                description:
                    "Premium milkfish belly marinated in our special blend of soy sauce, calamansi, and aromatic spices, then grilled to perfection over charcoal. Served with garlic rice, pickled vegetables, and spiced vinegar. Rich, flaky, and absolutely delicious.",
                price: 28,
                unit: "with rice",
                available: true,
                tags: [
                    {
                        label: "Chef's Special",
                        color: "#3b82f6",
                        textColor: "white",
                    },
                    {
                        label: "Gluten Free",
                        color: "primary",
                        textColor: "primary",
                    },
                    {
                        label: "High Protein",
                        color: "#10b981",
                        textColor: "white",
                    },
                ],
            },
        ],
    },
    {
        key: "desserts",
        name: "Desserts",
        description: "Sweet treats to end your meal perfectly",
        items: [
            {
                id: 5,
                name: "Leche Flan",
                description:
                    "Creamy caramel custard made with fresh eggs and condensed milk",
                price: 12,
                unit: "per slice",
                available: false,
                tags: [
                    {
                        label: "Traditional",
                        color: "secondary",
                        textColor: "secondary",
                    },
                ],
            },
            {
                id: 6,
                name: "Ube Ice Cream",
                description: "", // No description
                price: 8,
                unit: "per scoop",
                available: false,
                tags: [
                    {
                        label: "Local Favorite",
                        color: "#8b5cf6",
                        textColor: "white",
                    },
                ],
            },
        ],
    },
    {
        key: "beverages",
        name: "Beverages",
        description: "", // No description to test fallback
        items: [
            {
                id: 7,
                name: "Calamansi Juice",
                description: "", // No description to test fallback
                price: 5,
                unit: "per glass",
                available: true,
                tags: [
                    { label: "Fresh", color: "#10b981", textColor: "white" },
                    { label: "Local", color: "primary", textColor: "primary" },
                ],
            },
        ],
    },
]);

// Helper functions
const getCategoryItemCount = (category) => {
    if (!category.items || category.items.length === 0) return 0;
    return category.items.length;
};

const getAvailableItemCount = (category) => {
    if (!category.items || category.items.length === 0) return 0;
    return category.items.filter((item) => item.available).length;
};

const getCategoryStatus = (category) => {
    const total = getCategoryItemCount(category);
    const available = getAvailableItemCount(category);

    if (total === 0) return { label: "Coming Soon", color: "secondary" };
    if (available === 0)
        return { label: "Temporarily Unavailable", color: "#ef4444" };
    if (total === 1) return { label: "1 Item", color: "secondary" };
    return { label: `${total} Items`, color: "secondary" };
};

const getTagColor = (tag) => {
    if (tag.color === "primary") {
        return {
            backgroundColor: primaryColorWithAlpha(0.15),
            color: props.restaurant.config.primaryColor,
        };
    }
    if (tag.color === "secondary") {
        return {
            backgroundColor: secondaryColorWithAlpha(0.15),
            color:
                props.restaurant.config.secondaryColor ||
                props.restaurant.config.primaryColor,
        };
    }
    return { backgroundColor: tag.color, color: tag.textColor };
};

// Collapsible categories state
const expandedCategories = ref({
    appetizers: true, // Start with appetizers expanded to show skeleton loading
    mainCourses: true, // Start with main courses expanded to show the sample dishes
    desserts: false,
    beverages: false,
});

// Toggle category expanded state
const toggleCategory = (categoryKey) => {
    expandedCategories.value[categoryKey] =
        !expandedCategories.value[categoryKey];
};
</script>

<style scoped>
.font-playfair {
    font-family: "Playfair Display", serif;
}

/* Slide transition for collapsible categories */
.slide-enter-active,
.slide-leave-active {
    transition: all 0.3s ease-in-out;
    overflow: hidden;
}

.slide-enter-from {
    opacity: 0;
    max-height: 0;
}

.slide-enter-to {
    opacity: 1;
    max-height: 500px; /* Adjust based on your content height */
}

.slide-leave-from {
    opacity: 1;
    max-height: 500px;
}

.slide-leave-to {
    opacity: 0;
    max-height: 0;
}
</style>
