<template>
    <div>
        <!-- Hamburger for mobile -->
        <div class="flex items-center justify-between">
            <button
                class="md:hidden p-4 focus:outline-none"
                @click="open = !open"
                aria-label="Open sidebar"
            >
                <span class="text-4xl"> ☰ </span>
            </button>
            <div
                class="md:hidden p-6 font-bold text-xl flex items-center gap-2 max-w-54 min-w-54"
            >
                <Logo />
            </div>
        </div>
        <!-- Overlay for mobile when sidebar is open -->
        <div
            v-if="open"
            class="fixed inset-0 bg-black bg-opacity-30 opacity-50 z-30 md:hidden"
            @click="open = false"
        ></div>

        <!-- Sidebar -->
        <aside
            :class="[
                'fixed top-0 left-0 h-screen w-64 bg-gray-100 border-gray-200 border-r z-40 transform transition-transform duration-200 overflow-y-auto',
                open ? 'translate-x-0' : '-translate-x-full',
                'md:static md:translate-x-0 md:block md:h-screen md:sticky md:top-0',
            ]"
        >
            <div class="p-6 font-bold text-xl flex items-center gap-2">
                <Logo />
            </div>
            <nav>
                <ul>
                    <template v-for="item in navItems" :key="item.name">
                        <!-- Regular menu item -->
                        <li v-if="!item.type">
                            <router-link
                                :to="item.route"
                                class="flex mx-3 my-2 items-center gap-3 px-6 py-3 rounded-md hover:bg-gray-200 hover:text-gray-900 transition"
                                :class="{
                                    'bg-black text-white font-semibold':
                                        isActive(item.route),
                                    'text-gray-700': !isActive(item.route),
                                }"
                                :aria-current="
                                    isActive(item.route) ? 'page' : null
                                "
                                @click="open = false"
                            >
                                <span>{{ item.icon }}</span>
                                <span>{{ item.label }}</span>
                            </router-link>
                        </li>

                        <!-- Grouped menu items -->
                        <template v-else>
                            <li class="mt-4">
                                <div
                                    class="px-6 text-xs font-semibold text-gray-400 uppercase tracking-wider"
                                >
                                    {{ item.label }}
                                </div>
                                <ul class="mt-2">
                                    <li
                                        v-for="subItem in item.items"
                                        :key="subItem.name"
                                    >
                                        <router-link
                                            :to="subItem.route"
                                            class="flex mx-3 my-1 items-center gap-3 px-6 py-2 rounded-md hover:bg-gray-200 hover:text-gray-900 transition"
                                            :class="{
                                                'bg-black text-white font-semibold':
                                                    isActive(subItem.route),
                                                'text-gray-700': !isActive(
                                                    subItem.route
                                                ),
                                            }"
                                        >
                                            <span>{{ subItem.icon }}</span>
                                            <span>{{ subItem.label }}</span>
                                        </router-link>
                                    </li>
                                </ul>
                            </li>
                        </template>
                    </template>
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
import { ElMessageBox } from "element-plus";
import Logo from "@/components/Logo";
const open = ref(false);

const navItems = ref([
    // Dashboard
    { name: "dashboard", label: "Dashboard", route: "/dashboard", icon: "📊" },

    // Menu Management Group
    {
        type: "group",
        label: "Menu Management",
        items: [
            { name: "menus", label: "Menus", route: "/menus", icon: "🍽️" },
            {
                name: "menu-categories",
                label: "Categories",
                route: "/menu-categories",
                icon: "📑",
            },
            {
                name: "menu-items",
                label: "Menu Items",
                route: "/menu-items",
                icon: "🍔",
            },
            {
                name: "menu-tags",
                label: "Tags",
                route: "/tags",
                icon: "️🏷️",
            },
        ],
    },

    // Restaurant Management Group
    {
        type: "group",
        label: "Restaurant",
        items: [
            {
                name: "restaurants",
                label: "Restaurants",
                route: "/restaurants",
                icon: "🍔",
            },
        ],
    },

    // Account & Settings Group
    {
        type: "group",
        label: "Account",
        items: [
            {
                name: "account",
                label: "Profile",
                route: "/account",
                icon: "👤",
            },
            {
                name: "settings",
                label: "Settings",
                route: "/settings",
                icon: "⚙️",
            },
        ],
    },
]);

const route = useRoute();
const isActive = (path) => route.path.startsWith(path);

const authStore = useAuthStore();

// Add admin section if user is super admin
if (authStore.isSuperAdmin) {
    navItems.value.push({
        type: "group",
        label: "Administration",
        items: [
            {
                name: "users",
                label: "User Management",
                route: "/users",
                icon: "👥",
            },
        ],
    });
}
const router = useRouter();
const logout = async () => {
    ElMessageBox.confirm("Are you sure you want to logout?", "Logout", {
        confirmButtonText: "Logout",
        cancelButtonText: "Cancel",
        type: "warning",
    }).then(async () => {
        await authStore.logout();
        router.replace({ name: "login" });
    });
};
</script>
