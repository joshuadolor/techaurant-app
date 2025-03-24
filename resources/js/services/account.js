import ApiService from './api';

class AccountService {

    async register(credentials) {
        return ApiService.post('/users', credentials);
    }

}

export default new AccountService(); 