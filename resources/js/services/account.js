import ApiService from './api';
const API_URL = '/account';
class AccountService {

    async register(credentials) {
        return ApiService.post(`${API_URL}/register`, credentials);
    }

    async verifyEmail({ id, hash }) {
        return await ApiService.get(`${API_URL}/verify-email/${id}/${hash}`)
    }

    async forgotPassword(data) {
        return await ApiService.post(`${API_URL}/forgot-password`, data);
    }

    async resetPassword(data) {
        return await ApiService.post(`${API_URL}/reset-password`, data);
    }

}

export default new AccountService(); 