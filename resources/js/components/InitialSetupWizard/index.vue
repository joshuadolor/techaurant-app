<template>
    <div class="mx-auto p-2 sm:p-8 lg:p-8">
        <!-- Show welcome message if not started -->
        <WelcomeMessage v-if="!hasStarted" @start="startWizard" />

        <!-- Show wizard steps if started -->
        <div v-else>
            <!-- Progress Steps -->
            <el-steps
                :active="currentStep"
                finish-status="success"
                class="mb-8 max-w-2xl mx-auto"
                align-center
                process-status="processing"
            >
                <el-step status="processing" title="Restaurant Info" />
                <el-step status="processing" title="Menu Categories" />
                <el-step status="processing" title="Menu Items" />
                <el-step status="processing" title="Preview" />
            </el-steps>

            <!-- Step Content -->
            <div class="wizard-content p-2 py-4 md:p-8">
                <RestaurantInfoStep
                    v-if="currentStep === 1"
                    v-model="wizardData.restaurant"
                    @next="nextStep"
                    @back="previousStep"
                />

                <MenuCategoriesStep
                    v-if="currentStep === 2"
                    v-model="wizardData.categories"
                    @next="nextStep"
                    @back="previousStep"
                />

                <MenuItemsStep
                    v-if="currentStep === 3"
                    v-model="wizardData.items"
                    :categories="wizardData.categories"
                    @next="nextStep"
                    @back="previousStep"
                />

                <PreviewStep
                    v-if="currentStep === 4"
                    :wizard-data="wizardData"
                    @back="previousStep"
                    @finish="finishSetup"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import { useRouter } from "vue-router";
import { ElMessage } from "element-plus";
import RestaurantInfoStep from "./steps/RestaurantInfoStep.vue";
import MenuCategoriesStep from "./steps/MenuCategoriesStep.vue";
import MenuItemsStep from "./steps/MenuItemsStep.vue";
import PreviewStep from "./steps/PreviewStep.vue";
import WelcomeMessage from "./WelcomeMessage.vue";

const router = useRouter();
const currentStep = ref(1);

const startWizard = () => {
    hasStarted.value = true;
};

const wizardData = reactive({
    restaurant: {
        name: "",
        tagline: "",
        description: "",
        logo: "",
        contact: {
            phone: "",
            email: "",
            address: "",
        },
    },
    categories: [],
    items: [],
});
const hasStarted = ref(false);

const nextStep = () => {
    if (currentStep.value < 4) {
        currentStep.value++;
    }
};

const previousStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const finishSetup = async () => {
    try {
        // Save all data
        await saveWizardData(wizardData);
        // Redirect to dashboard
        router.push("/dashboard");
    } catch (error) {
        ElMessage.error("Failed to save setup data");
    }
};
</script>

<style scoped>
.wizard-content {
    background: white;
    border-radius: 8px;
    max-width: 900px;
    margin: 0 auto;
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
}
</style>
