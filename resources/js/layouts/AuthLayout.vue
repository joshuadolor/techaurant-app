<template>
    <div class="min-h-screen bg-base-200">
        <div class="navbar bg-base-100 shadow-lg">
            <div class="flex-1">
                <router-link to="/" class="btn btn-ghost text-xl"
                    >Your App</router-link
                >
            </div>
            <div class="flex-none">
                <ThemeSwitcher />
            </div>
        </div>

        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-center">
                <slot></slot>
            </div>
        </div>

        <footer class="footer footer-center p-4 bg-base-300 text-base-content">
            <div>
                <p>
                    Copyright © {{ new Date().getFullYear() }} - All rights
                    reserved
                </p>
            </div>
        </footer>
    </div>
</template>

<script setup>
import ThemeSwitcher from "@/components/Theme/ThemeSwitcher.vue";
</script>
