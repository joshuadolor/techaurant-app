<template>
    <section id="reservation" ref="sectionRef">
        <h2
            class="text-2xl lg:text-3xl font-bold text-slate-900 font-playfair mb-6 border-b-2 pb-2"
            :style="{ borderColor: restaurant.config.primaryColor }"
        >
            Make a Reservation
        </h2>
        <el-form @submit.prevent="submitReservation" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <el-input
                    v-model="form.name"
                    type="text"
                    placeholder="Full Name *"
                    size="large"
                    class="mb-4 md:mb-0"
                />
                <el-input
                    v-model="form.email"
                    type="email"
                    placeholder="Email Address *"
                    size="large"
                />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <el-date-picker
                    v-model="form.date"
                    type="date"
                    placeholder="Select Date *"
                    size="large"
                    :disabled-date="(date) => date < new Date()"
                    format="YYYY-MM-DD"
                    value-format="YYYY-MM-DD"
                />
                <el-select
                    v-model="form.time"
                    placeholder="Time *"
                    size="large"
                    class="w-full"
                >
                    <el-option
                        v-for="time in availableTimes"
                        :key="time"
                        :label="time"
                        :value="time"
                    />
                </el-select>
                <el-select
                    v-model="form.guests"
                    placeholder="Guests *"
                    size="large"
                    class="w-full"
                >
                    <el-option
                        v-for="size in [1, 2, 3, 4, 5, 6, 7, 8]"
                        :key="size"
                        :label="`${size} ${size === 1 ? 'Guest' : 'Guests'}`"
                        :value="size"
                    />
                </el-select>
            </div>

            <el-input
                v-model="form.message"
                type="textarea"
                :rows="4"
                placeholder="Special requests or dietary requirements..."
                resize="none"
                size="large"
            />

            <el-button
                type="primary"
                size="large"
                :loading="isSubmitting"
                :disabled="!isFormValid"
                native-type="submit"
                class="w-full"
            >
                {{ isSubmitting ? "Submitting..." : "Request Reservation" }}
            </el-button>
        </el-form>
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

// Form data
const form = ref({
    name: "",
    email: "",
    date: "",
    time: "",
    guests: "",
    message: "",
});

const isSubmitting = ref(false);

const availableTimes = [
    "5:00 PM",
    "5:30 PM",
    "6:00 PM",
    "6:30 PM",
    "7:00 PM",
    "7:30 PM",
    "8:00 PM",
    "8:30 PM",
    "9:00 PM",
    "9:30 PM",
];

const isFormValid = computed(() => {
    return (
        form.value.name &&
        form.value.email &&
        form.value.date &&
        form.value.time &&
        form.value.guests
    );
});

const submitReservation = async () => {
    if (!isFormValid.value) return;

    isSubmitting.value = true;

    try {
        await new Promise((resolve) => setTimeout(resolve, 1500));
        alert(
            `Thank you ${form.value.name}! Your reservation request has been submitted.`
        );

        form.value = {
            name: "",
            email: "",
            date: "",
            time: "",
            guests: "",
            message: "",
        };
    } catch (error) {
        alert("Sorry, there was an error. Please try again.");
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<style scoped>
.font-playfair {
    font-family: "Playfair Display", serif;
}
</style>
