<template>
    <div class="p-2">
        <SurveyComponent :model="survey" />
    </div>
</template>

<script setup>
import { Model } from "survey-core";
import { SurveyComponent } from "survey-vue3-ui";
// NOTE: Importing CSS from survey-core can fail in Vite depending on package exports.
// Load styles globally (CDN/link) or add a local stylesheet if needed.

const emit = defineEmits(["complete"]);

const json = {
    title: "Create Menu Item",
    showProgressBar: "top",
    progressBarType: "pages",
    firstPageIsStarted: true,
    startSurveyText: "Start",
    completedHtml: "",
    pages: [
        {
            name: "p0",
            elements: [
                { type: "text", name: "name", title: "Item name", isRequired: true, placeHolder: "e.g., Classic Burger" },
            ],
        },
        {
            name: "p1",
            elements: [
                { type: "comment", name: "description", title: "Short description", maxLength: 300, placeHolder: "Describe the item" },
            ],
        },
        {
            name: "p2",
            elements: [
                { type: "text", inputType: "number", name: "price", title: "Price ($)", min: 0, step: 0.01, isRequired: true, placeHolder: "0.00" },
                { type: "text", inputType: "number", name: "preparation_time", title: "Prep time (min)", min: 0, step: 1, placeHolder: "e.g., 10" },
            ],
        },
        {
            name: "p3",
            elements: [
                { type: "boolean", name: "is_active", title: "Active?", defaultValue: true, labelTrue: "Active", labelFalse: "Inactive" },
                { type: "boolean", name: "is_available", title: "Available?", defaultValue: true, labelTrue: "Available", labelFalse: "Unavailable" },
            ],
        },
        {
            name: "p4",
            elements: [
                { type: "text", name: "primary_image_url", title: "Image URL (optional)", placeHolder: "https://..." },
            ],
        },
    ],
};

const survey = new Model(json);
survey.onComplete.add((sender) => {
    emit("complete", sender.data);
});
</script>


