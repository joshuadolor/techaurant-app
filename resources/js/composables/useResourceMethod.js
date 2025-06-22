import { ref } from "vue";
import ResourceService from "@/services/resource";

export default function useResourceMethod(resourceName, { method }) {
    const loading = ref(false);
    const error = ref(null);

    const service = new ResourceService(resourceName);

    const execute = async (...args) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await service[method](...args);
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