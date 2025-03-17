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
        if (!authStore.isAuthenticated && !to.meta.public) {
            next({ name: "login" })
            return;
        }

        if (authStore.isAuthenticated && !authStore.user) {
            await authStore.fetchUser();
        }

        if (authStore.isAuthenticated && to.meta.public) {
            next({ name: "dashboard" });
            return;
        }

        next()
    })
}

export { router, setupRouterGuards };