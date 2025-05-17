import { defineStore } from 'pinia';
import AuthService from '@/services/auth';
import AccountService from "@/services/account";
import { AccountNotVerifiedError } from '@/utils/ErrorHandler';
import User from '@/models/User';
import { notify } from '@/utils/notification';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('token') || null,
        isAuthenticated: !!localStorage.getItem('token'),
        isLoading: false,
    }),

    actions: {
        setAlert(message, type = "success") {
            notify[type](message);
        },
        setAuthState(user, token) {
            this.user = new User(user);
            this.isAuthenticated = true;
            localStorage.setItem("token", token);
        },

        async login(credentials) {
            try {
                const response = await AuthService.login(credentials);
                const { user, token } = response.data;
                this.setAuthState(user, token);
                return response;
            } catch (error) {
                if (error instanceof AccountNotVerifiedError) {
                    const { user, token } = error.data;
                    this.setAuthState(user, token);
                }
                throw error;
            }
        },

        async register(userData) {
            try {
                const response = await AccountService.register(userData);
                this.setAlert("Registration successful! Please check your email to verify your account.");
                return response;
            } catch (error) {
                this.setAlert(error.response?.data?.message || "Registration failed", "error");
                throw error;
            }
        },

        async forgotPassword(email) {
            try {
                const response = await AccountService.forgotPassword({ email });
                this.setAlert("Password reset link has been sent to your email.");
                return response;
            } catch (error) {
                this.setAlert(error.response?.data?.message || "Failed to send reset link", "error");
                throw error;
            }
        },

        async resetPassword(data) {
            try {
                const response = await AccountService.resetPassword(data);
                this.setAlert("Password has been reset successfully!");
                return response;
            } catch (error) {
                this.setAlert(error.response?.data?.message || "Failed to reset password", "error");
                throw error;
            }
        },

        async logout() {
            try {
                await AuthService.logout();
                this.user = null;
                this.isAuthenticated = false;
                localStorage.removeItem("token");
                this.setAlert("Logged out successfully!");
            } catch (error) {
                this.setAlert(error.response?.data?.message || "Logout failed", "error");
                throw error;
            }
        },

        async checkAuth() {
            try {
                const response = await AccountService.getUser();
                this.user = response.data;
                this.isAuthenticated = true;
                return response;
            } catch (error) {
                this.clearAuth();
            }
        },

        clearAuth() {
            this.user = null;
            this.isAuthenticated = false;
            localStorage.removeItem("token");
        },

        async fetchUser() {
            try {
                const { data } = await AuthService.me();
                this.user = new User(data);
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
        },

        async resendVerification() {
            return await AuthService.resendVerification()
        },

        async verifyEmail({ id, hash }) {
            return await AuthService.verifyEmail({ id, hash })
        },

        async refresh() {
            const response = await AuthService.refresh();
            const { user, token } = response.data;
            this.setAuthState(user, token);
            return response;
        }
    },

    getters: {
        getUser: (state) => state.user || {},
        getToken: (state) => state.token,
        getIsAuthenticated: (state) => state.isAuthenticated,
    }
});