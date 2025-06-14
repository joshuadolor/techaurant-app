<!-- resources/js/layouts/AdminLayout.vue -->
<template>
    <div class="min-h-screen flex flex-col md:flex-row bg-gray-50">
        <!-- Sidebar -->
        <Sidebar />

        <!-- Main content area -->
        <div class="flex-1 flex flex-col bg-gray-50">
            <!-- Topbar (optional) -->
            <header
                class="flex items-center justify-between px-4 py-2 border-b border-gray-200 bg-white shadow-sm sticky top-0 z-10"
            >
                <div class="flex items-center gap-2">
                    <button class="md:hidden" @click="toggleSidebar">
                        <span class="text-2xl">☰</span>
                    </button>
                    <span class="font-semibold text-lg">Dashboard</span>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Placeholder for search, notifications, user menu -->
                    <input
                        type="text"
                        placeholder="Search..."
                        class="hidden md:block border-gray-400 border rounded px-2 py-1"
                    />
                    <button class="text-xl">🔔</button>
                    <button class="text-xl">⚙️</button>
                </div>
            </header>
            <!-- Main slot for page content -->
            <main class="flex-1 p-4">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import Sidebar from "@/components/Sidebar";
const toggleSidebar = () => {
    // Optionally, you can use an event bus or state to control sidebar from header
};
</script>
