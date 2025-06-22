import { ref } from "vue";
import ApiService from "@/services/api";

export default function useApiMethod({ service, method }) {
    const loading = ref(false);
    const error = ref(null);

    // Handle any API method
    const execute = async (params = {}) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await ApiService[method](service, params);
            return response.data;
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
    };
}