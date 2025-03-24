import ApiService from './api';
const API_URL = '/auth';
class AuthService {

    async socialLogin(provider) {
        return ApiService.get(`/auth/${provider}`);
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

    async verifyEmail({ id, hash }) {
        return await ApiService.get(`${API_URL}/verify-email/${id}/${hash}`)
    }

    async forgotPassword(email) {
        return ApiService.post(`${API_URL}/forgot-password`, { email });
    }

    async resetPassword(data) {
        return ApiService.post(`${API_URL}/reset-password`, data);
    }
}

export default new AuthService(); 