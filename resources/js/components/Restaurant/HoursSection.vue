<template>
    <section id="hours" ref="sectionRef" v-if="restaurant.businessHoursByDay">
        <h2
            class="text-2xl lg:text-3xl font-bold text-slate-900 font-playfair mb-6 border-b-2 pb-2"
            :style="{ borderColor: restaurant.config.primaryColor }"
        >
            Weekly Schedule
        </h2>
        <div
            class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden"
        >
            <div class="divide-y divide-slate-100">
                <div
                    v-for="(dayData, dayName) in restaurant.businessHoursByDay"
                    :key="dayName"
                    class="px-6 py-4 hover:bg-slate-50/50 transition-all duration-200 group"
                    :class="{
                        'bg-gradient-to-r from-transparent via-transparent to-transparent':
                            !isToday(dayName),
                    }"
                    :style="
                        isToday(dayName)
                            ? {
                                  background: `linear-gradient(90deg, ${primaryColorWithAlpha(
                                      0.03
                                  )} 0%, ${primaryColorWithAlpha(
                                      0.08
                                  )} 50%, ${primaryColorWithAlpha(0.03)} 100%)`,
                              }
                            : {}
                    "
                >
                    <div class="flex items-center justify-between">
                        <!-- Day Name -->
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center space-x-3">
                                <div
                                    v-if="isToday(dayName)"
                                    class="w-2 h-2 rounded-full"
                                    :style="{
                                        backgroundColor:
                                            restaurant.config.primaryColor,
                                    }"
                                ></div>
                                <div
                                    v-else
                                    class="w-2 h-2 rounded-full bg-slate-300 group-hover:bg-slate-400 transition-colors"
                                ></div>
                                <h3
                                    class="font-semibold text-xl transition-colors"
                                    :class="
                                        isToday(dayName)
                                            ? 'text-slate-900'
                                            : 'text-slate-700 group-hover:text-slate-900'
                                    "
                                >
                                    {{ dayName }}
                                </h3>
                            </div>
                            <div
                                v-if="isToday(dayName)"
                                class="px-2.5 py-1 text-xs font-medium text-white rounded-full"
                                :style="{
                                    backgroundColor:
                                        restaurant.config.primaryColor,
                                }"
                            >
                                Today
                            </div>
                        </div>

                        <!-- Hours -->
                        <div class="text-right">
                            <div
                                v-if="dayData.data.isClosed"
                                class="text-slate-500 font-medium"
                            >
                                Closed
                            </div>
                            <div v-else class="space-y-1">
                                <div
                                    v-for="(
                                        period, index
                                    ) in dayData.timePeriods"
                                    :key="index"
                                    class="flex items-center justify-end space-x-2"
                                >
                                    <span class="text-slate-900 font-medium">
                                        {{ period.openTime12h }}
                                    </span>
                                    <div class="flex items-center space-x-1">
                                        <div
                                            class="w-2 h-0.5 bg-slate-400 rounded-full"
                                        ></div>
                                    </div>
                                    <span class="text-slate-900 font-medium">
                                        {{ period.closeTime12h }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, computed } from "vue";
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

// Today's info
const today = computed(() => {
    return new Date().toLocaleDateString("en", { weekday: "long" });
});

const isToday = (dayName) => {
    return dayName === today.value;
};
</script>

<style scoped>
.font-playfair {
    font-family: "Playfair Display", serif;
}
</style>
