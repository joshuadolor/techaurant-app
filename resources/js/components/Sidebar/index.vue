<!-- resources/js/components/Sidebar.vue -->
<template>
    <div>
        <!-- Hamburger for mobile -->
        <button
            class="md:hidden p-4 focus:outline-none"
            @click="open = !open"
            aria-label="Open sidebar"
        >
            <span class="text-2xl">☰</span>
        </button>

        <!-- Overlay for mobile when sidebar is open -->
        <div
            v-if="open"
            class="fixed inset-0 bg-black bg-opacity-30 z-30 md:hidden"
            @click="open = false"
        ></div>

        <!-- Sidebar -->
        <aside
            :class="[
                'fixed top-0 left-0 h-full w-64 bg-gray-100 border-gray-200 border-r z-40 transform transition-transform duration-200',
                open ? 'translate-x-0' : '-translate-x-full',
                'md:static md:translate-x-0 md:block',
            ]"
        >
            <div class="p-6 font-bold text-xl flex items-center gap-2">
                <span>🏠</span>
                <span>TechnoResto</span>
            </div>
            <nav>
                <ul>
                    <li v-for="item in navItems" :key="item.name">
                        <router-link
                            :to="item.route"
                            class="flex mx-3 my-2 items-center gap-3 px-6 py-3 rounded-md hover:bg-gray-200 hover:text-gray-900 transition"
                            :class="{
                                'bg-black text-white font-semibold': isActive(
                                    item.route
                                ),
                                'text-gray-700': !isActive(item.route),
                            }"
                            :aria-current="isActive(item.route) ? 'page' : null"
                            @click="open = false"
                        >
                            <span>{{ item.icon }}</span>
                            <span>{{ item.label }}</span>
                        </router-link>
                    </li>
                    <li>
                        <a
                            role="button"
                            class="flex mx-3 cursor-pointer my-2 items-center gap-3 px-6 py-3 rounded-md hover:bg-gray-200 hover:text-gray-900 transition"
                            @click="logout"
                        >
                            <span>🔒</span>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";

const open = ref(false);

const navItems = [
    { name: "dashboard", label: "Dashboard", route: "/dashboard", icon: "📊" },
    { name: "users", label: "User Management", route: "/users", icon: "👥" },
    { name: "account", label: "Account", route: "/account", icon: "📝" },
    {
        name: "restaurants",
        label: "Restaurants",
        route: "/restaurants",
        icon: "🍔",
    },
    { name: "menus", label: "Menus", route: "/menus", icon: "🍽️" },
    { name: "settings", label: "Settings", route: "/settings", icon: "⚙️" },
];

const route = useRoute();
const isActive = (path) => route.path.startsWith(path);

const authStore = useAuthStore();
const router = useRouter();
const logout = async () => {
    await authStore.logout();
    router.replace({ name: "login" });
};
</script>
