import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import routes from './routes'

const router = createRouter({
    history: createWebHistory(),
    routes
})

const setupRouterGuards = () => {
    const authStore = useAuthStore();

    router.beforeEach(async (to, from, next) => {
        const isResendVerification = to.name === "resend-verification";
        const isVerifyEmail = to.name === "verify-email";
        const isDashboard = to.name === "dashboard";

        // 1. Not authenticated and route is not public: redirect to login
        if (!authStore.isAuthenticated && !to.meta.public) {
            next({ name: "login" });
            return;
        }

        if (isVerifyEmail) {
            next();
            return;
        }

        // 2. If authenticated but user data not loaded, fetch it
        if (authStore.isAuthenticated && !authStore.user) {
            await authStore.fetchUser();
        }

        // 3. If authenticated and user is unverified
        if (authStore.isAuthenticated && !authStore.user.isVerified) {
            // Only allow access to resend-verification and logout
            if (!isResendVerification && to.name !== "logout") {
                next({ name: "resend-verification" });
                return;
            }
            // If already on resend-verification, allow navigation
            next();
            return;
        }

        // 4. If authenticated and user is verified
        if (authStore.isAuthenticated && authStore.user.isVerified) {
            // Prevent access to resend-verification page
            if (isResendVerification) {
                next({ name: "dashboard" });
                return;
            }
            // Optionally, redirect to dashboard if on a public route (like login/register)
            if (to.meta.public && !isDashboard) {
                next({ name: "dashboard" });
                return;
            }
        }

        // 5. Default: allow navigation
        next();
    })
}

export { router, setupRouterGuards };