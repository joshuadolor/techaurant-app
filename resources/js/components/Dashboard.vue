<template>
    <div>
        <h1>Dashboard</h1>
        <button
            class="bg-blue-500 text-white px-4 py-2 rounded"
            @click="logout"
        >
            Logout
        </button>
    </div>
</template>

<script>
export default {
    name: "Dashboard",
    methods: {
        logout() {
            localStorage.removeItem("token");
            window.location.href = "/login";
        },
    },
};
</script>
