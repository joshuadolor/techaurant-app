<template>
    <div class="welcome-message">
        <!-- Skip Button -->
        <div class="absolute top-4 right-4">
            <el-button text @click="skipSetup">
                Skip Setup
                <el-icon class="ml-1"><ArrowRight /></el-icon>
            </el-button>
        </div>

        <div class="max-w-4xl mx-auto text-center p-1 md:px-4">
            <!-- Animated Logo/Icon with Progress -->
            <div class="welcome-icon w-25 h-25 mx-auto mb-6 md:mb-8 relative">
                <SmallLogo />
            </div>

            <!-- Welcome Text with Typing Effect -->
            <h1
                class="text-2xl text-[#f08a5c] md:text-4xl font-bold mb-3 md:mb-4 typing-effect"
            >
                Welcome to TechnoResto!
            </h1>

            <p class="text-lg md:text-xl text-gray-600 mb-6 md:mb-8 fade-in">
                Let's set up your restaurant's digital presence in just a few
                steps
            </p>

            <!-- Feature Cards with Enhanced Animation -->
            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6 mb-8 md:mb-12"
            >
                <el-card
                    v-for="(feature, index) in features"
                    :key="index"
                    class="feature-card"
                    :class="`delay-${index * 100}`"
                    shadow="hover"
                >
                    <el-icon
                        :size="24"
                        class="text-primary-500 mb-3 md:mb-4 feature-icon"
                    >
                        <component :is="feature.icon" />
                    </el-icon>
                    <h3 class="text-base md:text-lg font-semibold mb-2">
                        {{ feature.title }}
                    </h3>
                    <p class="text-sm md:text-base text-gray-600">
                        {{ feature.description }}
                    </p>
                </el-card>
            </div>

            <!-- Estimated Time with Progress -->
            <div class="mt-4 hidden md:block">
                <el-button
                    type="primary"
                    size="large"
                    class="w-full md:w-auto px-6 md:px-8 py-2 md:py-3 text-base md:text-lg pulse-button"
                    @click="startWizard"
                >
                    Get Started
                    <el-icon class="ml-2"><ArrowRight /></el-icon>
                </el-button>
            </div>
        </div>

        <!-- Fixed Get Started Button -->
        <div
            class="fixed bottom-0 left-0 right-0 md:hidden p-4 bg-gradient-to-t from-white to-transparent"
        >
            <el-button
                type="primary"
                size="large"
                class="w-full md:w-auto px-6 md:px-8 py-2 md:py-3 text-base md:text-lg pulse-button"
                @click="startWizard"
            >
                Get Started
                <el-icon class="ml-2"><ArrowRight /></el-icon>
            </el-button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import {
    Shop,
    Menu,
    Monitor,
    Setting,
    ArrowRight,
    Money,
    DataLine,
    Connection,
} from "@element-plus/icons-vue";
import { ElMessage } from "element-plus";
import SmallLogo from "@/components/Logo/SmallLogo";

const emit = defineEmits(["start"]);
const progress = ref(0);
const router = useRouter();
const features = [
    {
        icon: Menu,
        title: "Menu Management",
        description: "Create and organize your menu items with ease",
    },
    {
        icon: Monitor,
        title: "Online Presence",
        description: "Get your own restaurant website instantly",
    },
    {
        icon: Setting,
        title: "Easy Customization",
        description:
            "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.",
    },
    {
        icon: Money,
        title: "QR Code Management",
        description:
            "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.",
    },
    {
        icon: DataLine,
        title: "Analytics Dashboard",
        description: "Track your restaurant's performance",
    },
];

const startWizard = () => {
    progress.value = 100;
    setTimeout(() => {
        emit("start");
    }, 500);
};

const skipSetup = () => {
    ElMessage({
        message:
            "You can always access the setup guide from your dashboard settings",
        duration: 4000,
    });

    router.replace({
        name: "dashboard",
    });
};

onMounted(() => {
    // Animate progress
    const interval = setInterval(() => {
        if (progress.value < 90) {
            progress.value += 10;
        } else {
            clearInterval(interval);
        }
    }, 500);
});
</script>

<style scoped>
.welcome-message {
    padding: 2rem 1rem;
    background: linear-gradient(to bottom, #f8fafc, #ffffff);
    min-height: 90vh;
    display: flex;
    align-items: center;
    position: relative;
}

.feature-card {
    transition: all 0.3s ease;
    text-align: center;
    padding: 0.75rem;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.5s ease forwards;
}

.feature-card:hover {
    transform: translateY(-5px);
}

.feature-icon {
    transition: all 0.3s ease;
}

.feature-card:hover .feature-icon {
    transform: scale(1.2);
}

.progress-ring {
    animation: rotate 2s linear infinite;
}

.pulse-button {
    animation: pulse 2s infinite;
}

/* Animations */
@keyframes float {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
    100% {
        transform: translateY(0px);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Delay classes for staggered animations */
.delay-100 {
    animation-delay: 0.1s;
}
.delay-200 {
    animation-delay: 0.2s;
}
.delay-300 {
    animation-delay: 0.3s;
}
.delay-400 {
    animation-delay: 0.4s;
}
.delay-500 {
    animation-delay: 0.5s;
}

/* Typing effect */
.typing-effect {
    overflow: hidden;
    border-right: 2px solid #000;
    white-space: nowrap;
    animation: typing 3.5s steps(40, end), blink-caret 0.75s step-end infinite;
    max-width: 100%;
}

@keyframes typing {
    from {
        width: 0;
    }
    to {
        width: 100%;
    }
}

@keyframes blink-caret {
    from,
    to {
        border-color: transparent;
    }
    50% {
        border-color: #000;
    }
}

/* Fade in effect */
.fade-in {
    opacity: 0;
    animation: fadeIn 1s ease forwards;
    animation-delay: 1s;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Update pulse animation to be less aggressive on mobile */
@media (max-width: 768px) {
    .pulse-button {
        animation: pulse 2s infinite;
        transform-origin: center;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.02);
        }
        100% {
            transform: scale(1);
        }
    }
}
</style>
