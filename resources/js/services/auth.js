import ApiService from './api';
const API_URL = '/auth';
class AuthService {

    async socialLogin(provider) {
        return ApiService.get(`/auth/social/${provider}`);
    }

    async login(credentials) {
        return ApiService.post(`${API_URL}/login`, credentials);
    }

    async logout() {
        return ApiService.post(`${API_URL}/logout`);
    }

    async resendVerification() {
        return await ApiService.post(`${API_URL}/email/verification-notification`)
    }

    async resetPassword(data) {
        return ApiService.post(`${API_URL}/reset-password`, data);
    }

    async me() {
        return ApiService.get(`${API_URL}/me`);
    }

    async refresh() {
        return ApiService.post(`${API_URL}/refresh`);
    }

    async logout() {
        return ApiService.get(`${API_URL}/logout`);
    }

    async verifyEmail({ id, hash }) {
        return await ApiService.get(`${API_URL}/verify-email/${id}/${hash}`)
    }
}

export default new AuthService(); 