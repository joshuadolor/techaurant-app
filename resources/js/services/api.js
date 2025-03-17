import axios from '@/axios';
import { createError } from '@/utils/ErrorHandler';

class ApiService {
    async request(method, url, data = null, config = {}) {
        try {
            const response = await axios[method](url, data, config);
            return response.data;
        } catch (error) {
            const apiError = createError(error);
            throw apiError; // Throw our custom error
        }
    }

    get(url, config = {}) {
        return this.request('get', url, null, config);
    }

    post(url, data, config = {}) {
        return this.request('post', url, data, config);
    }

    put(url, data, config = {}) {
        return this.request('put', url, data, config);
    }

    delete(url, config = {}) {
        return this.request('delete', url, null, config);
    }
}

export default new ApiService(); 