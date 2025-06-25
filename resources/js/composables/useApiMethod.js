import { ref } from "vue";
import ApiService from "@/services/api";
import Cache from "@/utils/Cache";

export default function useApiMethod({ service, method, shouldCache = false }) {
    const loading = ref(false);
    const error = ref(null);
    const data = ref(null);
    const cacheKey = `${method}-${service}`;

    const execute = async (params = {}) => {
        loading.value = true;
        error.value = null;
        try {
            if (shouldCache && Cache.has(cacheKey)) {
                loading.value = false;
                data.value = Cache.get(cacheKey);
                return;
            }
            const response = await ApiService[method](service, params);
            data.value = response.data;
            if (shouldCache) {
                Cache.set(cacheKey, response.data);
            }
        } catch (err) {
            error.value = err;
            throw err;
        } finally {
            loading.value = false;
        }
    };

    return {
        loading,
        error,
        execute,
        data,
    };
}