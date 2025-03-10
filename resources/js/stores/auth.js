import { defineStore } from 'pinia';
import axios from '@/axios';

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
                const { data } = await axios.post("/login", credentials);
                if (data.token) {
                    this.setAuth(data.token, data.user);
                    return data;
                }
                throw new Error('No token received');
            } catch (error) {
                this.clearAuth();
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        logout() {
            // try {
            //     await axios.post("/logout");
            // } catch (error) {
            //     console.error('Logout error:', error);
            // } finally {
            this.clearAuth();
            // Redirect to login page after clearing auth
            //router.push({ name: 'login' });
            return true;
            // }
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
                const { data } = await axios.get(`/auth/${provider}`);
                return data.url;
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