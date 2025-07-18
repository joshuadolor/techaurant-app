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

    async create(data, config = {}) {
        return ApiService.post(`${this.API_URL}`, data, config);
    }

    async update(id, data, config = {}, { isFormData = false, method = 'put' } = {}) {
        const cleanData = Object.fromEntries(
            Object.entries(data).filter(
                ([_, value]) => value !== "" && value !== null
            )
        );
        return ApiService[method](`${this.API_URL}/${id}`, isFormData ? data : cleanData, config);
    }

    async delete(id) {
        return ApiService.delete(`${this.API_URL}/${id}`);
    }

}

export default ResourceService; 