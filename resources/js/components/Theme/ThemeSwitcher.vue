<template>
    <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="btn m-1">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                class="w-5 h-5 stroke-current"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                ></path>
            </svg>
            Theme
        </div>
        <ul
            tabindex="0"
            class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52"
        >
            <li v-for="theme in themes" :key="theme">
                <a
                    @click="setTheme(theme)"
                    :class="{ active: currentTheme === theme }"
                >
                    {{ theme }}
                </a>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const themes = ["light", "dark"];
const currentTheme = ref("light");

const setTheme = (theme) => {
    document.documentElement.setAttribute("data-theme", theme);
    currentTheme.value = theme;
    localStorage.setItem("theme", theme);
};

onMounted(() => {
    const savedTheme = localStorage.getItem("theme") || "light";
    setTheme(savedTheme);
});
</script>
