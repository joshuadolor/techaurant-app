<template>
    <section id="reviews" ref="sectionRef">
        <h2
            class="text-2xl lg:text-3xl font-bold text-slate-900 font-playfair mb-6 border-b-2 pb-2"
            :style="{ borderColor: props.restaurant.config.primaryColor }"
        >
            Customer Reviews
        </h2>

        <!-- Review Summary -->
        <div class="mb-8 text-center">
            <div class="flex items-center justify-center space-x-4 mb-4">
                <div class="text-4xl font-bold text-slate-900">
                    {{ averageRating.toFixed(1) }}
                </div>
                <div>
                    <div
                        class="flex items-center justify-center space-x-1 mb-2"
                    >
                        <div
                            v-for="i in 5"
                            :key="i"
                            class="w-6 h-6 transition-colors"
                            :class="
                                i <= Math.round(averageRating)
                                    ? 'text-yellow-400'
                                    : 'text-gray-300'
                            "
                        >
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                />
                            </svg>
                        </div>
                    </div>
                    <p class="text-slate-600 text-sm">
                        Based on {{ reviews.length }} reviews
                    </p>
                </div>
            </div>
        </div>

        <!-- Write Review Form -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8"
        >
            <h3 class="text-xl font-semibold text-slate-900 mb-4">
                Write a Review
            </h3>
            <el-form @submit.prevent="submitReview" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <el-input
                        v-model="reviewForm.name"
                        placeholder="Your Name *"
                        size="large"
                    />
                    <el-input
                        v-model="reviewForm.email"
                        type="email"
                        placeholder="Email Address (optional)"
                        size="large"
                    />
                </div>

                <div>
                    <label
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Your Rating *
                    </label>
                    <el-rate
                        v-model="reviewForm.rating"
                        :colors="rateColors"
                        size="large"
                        show-text
                        :texts="[
                            'Poor',
                            'Fair',
                            'Good',
                            'Very Good',
                            'Excellent',
                        ]"
                    />
                </div>

                <el-input
                    v-model="reviewForm.comment"
                    type="textarea"
                    :rows="4"
                    placeholder="Share your experience with others..."
                    resize="none"
                    size="large"
                />

                <el-button
                    type="primary"
                    size="large"
                    :loading="isSubmittingReview"
                    :disabled="!isReviewValid"
                    native-type="submit"
                    class="w-full"
                >
                    {{ isSubmittingReview ? "Submitting..." : "Submit Review" }}
                </el-button>
            </el-form>
        </div>

        <!-- Reviews Display -->
        <div class="space-y-4">
            <div
                v-for="review in reviews"
                :key="review.id"
                class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow duration-200"
            >
                <div class="flex items-start space-x-4">
                    <!-- Avatar -->
                    <div
                        class="w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold text-lg"
                        :style="{
                            backgroundColor:
                                props.restaurant.config.primaryColor,
                        }"
                    >
                        {{ review.name.charAt(0).toUpperCase() }}
                    </div>

                    <!-- Review Content -->
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h4 class="font-semibold text-slate-900">
                                    {{ review.name }}
                                </h4>
                                <p class="text-sm text-slate-500">
                                    {{ review.date }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-1">
                                <div
                                    v-for="i in 5"
                                    :key="i"
                                    class="w-4 h-4 transition-colors"
                                    :class="
                                        i <= review.rating
                                            ? 'text-yellow-400'
                                            : 'text-gray-300'
                                    "
                                >
                                    <svg
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <p class="text-slate-700 leading-relaxed">
                            {{ review.comment }}
                        </p>

                        <!-- Helpful buttons (for future functionality) -->
                        <div
                            class="flex items-center space-x-4 mt-4 pt-4 border-t border-slate-100"
                        >
                            <button
                                class="flex items-center space-x-1 text-sm text-slate-500 hover:text-slate-700 transition-colors"
                            >
                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L9 7m5 3v4M9 7H7l-2 5m2-5v6m2-1h2m-2-5z"
                                    />
                                </svg>
                                <span>Helpful ({{ review.helpful }})</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Load More Button (for future pagination) -->
        <div class="text-center mt-8" v-if="reviews.length >= 5">
            <el-button size="large" class="px-8"> Load More Reviews </el-button>
        </div>
    </section>
</template>

<script setup>
import { ref, computed } from "vue";

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

// Review form
const reviewForm = ref({
    name: "",
    email: "",
    rating: 0,
    comment: "",
});

const isSubmittingReview = ref(false);

const isReviewValid = computed(() => {
    return (
        reviewForm.value.name &&
        reviewForm.value.rating > 0 &&
        reviewForm.value.comment
    );
});

const rateColors = computed(() => ({
    2: props.restaurant.config.primaryColor,
    4: props.restaurant.config.primaryColor,
    5: props.restaurant.config.primaryColor,
}));

// Dummy reviews data
const reviews = ref([
    {
        id: 1,
        name: "Sarah Johnson",
        rating: 5,
        comment:
            "Absolutely fantastic experience! The food was incredible, service was top-notch, and the atmosphere was perfect for our anniversary dinner. Will definitely be coming back!",
        date: "2 days ago",
        helpful: 8,
    },
    {
        id: 2,
        name: "Mike Chen",
        rating: 4,
        comment:
            "Great food and wonderful ambiance. The pasta was perfectly cooked and the wine selection was excellent. Only minor complaint was the wait time, but it was worth it!",
        date: "1 week ago",
        helpful: 5,
    },
    {
        id: 3,
        name: "Emily Rodriguez",
        rating: 5,
        comment:
            "This place exceeded all my expectations! Every dish was a masterpiece. The staff was incredibly knowledgeable about the menu and made great recommendations.",
        date: "2 weeks ago",
        helpful: 12,
    },
    {
        id: 4,
        name: "David Thompson",
        rating: 4,
        comment:
            "Solid restaurant with good food and service. The steak was cooked to perfection and the sides were delicious. Pricing is fair for the quality you get.",
        date: "3 weeks ago",
        helpful: 3,
    },
    {
        id: 5,
        name: "Lisa Park",
        rating: 5,
        comment:
            "Outstanding in every way! From the moment we walked in, we were treated like VIPs. The chef's special was phenomenal and the dessert was to die for!",
        date: "1 month ago",
        helpful: 15,
    },
]);

const averageRating = computed(() => {
    if (reviews.value.length === 0) return 0;
    const total = reviews.value.reduce((sum, review) => sum + review.rating, 0);
    return total / reviews.value.length;
});

const submitReview = async () => {
    if (!isReviewValid.value) return;

    isSubmittingReview.value = true;

    try {
        // Simulate API call
        await new Promise((resolve) => setTimeout(resolve, 1500));

        // Add to reviews list (in real app, this would come from API response)
        reviews.value.unshift({
            id: Date.now(),
            name: reviewForm.value.name,
            rating: reviewForm.value.rating,
            comment: reviewForm.value.comment,
            date: "Just now",
            helpful: 0,
        });

        // Reset form
        reviewForm.value = {
            name: "",
            email: "",
            rating: 0,
            comment: "",
        };

        alert("Thank you for your review! It has been submitted successfully.");
    } catch (error) {
        alert(
            "Sorry, there was an error submitting your review. Please try again."
        );
    } finally {
        isSubmittingReview.value = false;
    }
};
</script>

<style scoped>
.font-playfair {
    font-family: "Playfair Display", serif;
}
</style>
