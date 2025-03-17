import ApiService from './api';

class AccountService {

    async register(credentials) {
        return ApiService.post('/register', credentials);
    }

}

export default new AccountService(); 