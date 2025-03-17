import { defineStore } from 'pinia';
import AuthService from '@/services/auth';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('token') || null,
        isAuthenticated: !!localStorage.getItem('token'),
        isLoading: false,
    }),

    actions: {
        async login(credentials) {
            try {
                this.isLoading = true;
                const data = await AuthService.login(credentials);
                this.setAuth(data.token, data.user);
                return data;
            } catch (error) {
                this.clearAuth();
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async logout() {
            await AuthService.logout();
            this.clearAuth();
        },

        setAuth(token, user) {
            this.token = token;
            this.user = user;
            this.isAuthenticated = true;
            localStorage.setItem('token', token);
        },

        clearAuth() {
            this.token = null;
            this.user = null;
            this.isAuthenticated = false;
            localStorage.removeItem('token');
            delete axios.defaults.headers.common['Authorization'];
        },

        async fetchUser() {
            try {
                const { data } = await axios.get('/me');
                this.user = data;
            } catch (error) {
                this.clearAuth();
                throw error;
            }
        },

        async handleSocialLogin(provider) {
            try {
                const { url } = await AuthService.socialLogin(provider);
                return url;
            } catch (error) {
                console.error(`Failed to initiate ${provider} login:`, error);
                throw error;
            }
        }
    },

    getters: {
        getUser: (state) => state.user || {},
        getToken: (state) => state.token,
        isLoggedIn: (state) => state.isAuthenticated,
    }
});