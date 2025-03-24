import { defineStore } from 'pinia';
import AuthService from '@/services/auth';
import AccountService from "@/services/account";

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('token') || null,
        isAuthenticated: !!localStorage.getItem('token'),
        isLoading: false,
        alert: {
            show: false,
            message: "",
            type: "success",
        },
    }),

    actions: {
        setAlert(message, type = "success") {
            this.alert = {
                show: true,
                message,
                type,
            };
            setTimeout(() => {
                this.alert.show = false;
            }, 3000);
        },

        async login(credentials) {
            try {
                const response = await AuthService.login(credentials);
                this.user = response.data.user;
                this.isAuthenticated = true;
                localStorage.setItem("token", response.data.token);
                this.setAlert("Login successful!");
                return response;
            } catch (error) {
                this.setAlert(error.response?.data?.message || "Login failed", "error");
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
                await AccountService.logout();
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
                this.user = null;
                this.isAuthenticated = false;
                localStorage.removeItem("token");
                throw error;
            }
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
        },

        async resendVerification() {
            return await AuthService.resendVerification()
        },

        async verifyEmail({ id, hash }) {
            return await AuthService.verifyEmail({ id, hash })
        },
    },

    getters: {
        getUser: (state) => state.user || {},
        getToken: (state) => state.token,
        getIsAuthenticated: (state) => state.isAuthenticated,
        getAlert: (state) => state.alert,
    }
});