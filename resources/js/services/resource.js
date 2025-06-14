import ApiService from './api';
class ResourceService {

    constructor(resource) {
        this.API_URL = `/resource/${resource}`;
    }

    async getAll(params) {
        return ApiService.get(`${this.API_URL}`, params);
    }

    async get(id) {
        return ApiService.get(`${this.API_URL}/${id}`);
    }

    async create(data) {
        return ApiService.post(`${this.API_URL}`, data);
    }

    async update(id, data) {
        return ApiService.put(`${this.API_URL}/${id}`, data);
    }

    async delete(id) {
        return ApiService.delete(`${this.API_URL}/${id}`);
    }

}

export default ResourceService; 