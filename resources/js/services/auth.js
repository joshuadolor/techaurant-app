import ApiService from './api';

class AuthService {
    async login(credentials) {
        return ApiService.post('/login', credentials);
    }

    async logout() {
        return ApiService.post('/logout');
    }

    async getUser() {
        return ApiService.get('/api/user');
    }

    async socialLogin(provider) {
        return ApiService.get(`/auth/${provider}`);
    }
}

export default new AuthService(); 