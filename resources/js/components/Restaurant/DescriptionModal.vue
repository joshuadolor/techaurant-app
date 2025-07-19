<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div
            class="absolute inset-0 bg-black/50 backdrop-blur-sm modal-backdrop cursor-pointer"
            @click="$emit('close')"
        ></div>

        <!-- Modal Content -->
        <div
            class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-hidden modal-content"
        >
            <!-- Header -->
            <div
                class="flex items-center justify-between p-6 border-b border-slate-200"
                style="background-color: var(--bg-primary)"
            >
                <div>
                    <h3 class="text-2xl font-bold text-slate-900 font-playfair">
                        About {{ restaurant.name }}
                    </h3>
                    <p class="text-sm text-slate-600 mt-1">
                        {{ restaurant.tagline }}
                    </p>
                </div>
                <button
                    @click="$emit('close')"
                    class="p-2 rounded-full hover:bg-slate-100 transition-colors cursor-pointer"
                >
                    <svg
                        class="w-6 h-6 text-slate-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="p-6 overflow-y-auto max-h-96">
                <p
                    class="text-base text-slate-700 leading-relaxed whitespace-pre-line"
                >
                    {{ restaurant.description }}
                </p>
            </div>

            <!-- Footer -->
            <div
                class="flex justify-end p-6 border-t border-slate-200 bg-slate-50"
            >
                <button
                    @click="$emit('close')"
                    class="px-6 py-3 text-white rounded-lg font-semibold transition-all duration-200 hover:shadow-lg cursor-pointer"
                    style="background-color: var(--primary-color)"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, onUnmounted } from "vue";

const props = defineProps({
    restaurant: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["close"]);

// Handle modal keyboard events
const handleKeydown = (event) => {
    if (event.key === "Escape") {
        emit("close");
    }
};

onMounted(() => {
    document.addEventListener("keydown", handleKeydown);
    document.body.style.overflow = "hidden";
});

onUnmounted(() => {
    document.removeEventListener("keydown", handleKeydown);
    document.body.style.overflow = "";
});
</script>

<style scoped>
.font-playfair {
    font-family: "Playfair Display", serif;
}

/* Modal animations */
.modal-backdrop {
    animation: fadeIn 0.3s ease-out;
}

.modal-content {
    animation: slideIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
</style>
