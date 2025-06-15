import { ref, watch, reactive } from "vue";
import ResourceService from "@/services/resource";

export default function useResourceCrudTable(resourceName, model = null, initialQuery = {}) {
    const resourceService = new ResourceService(resourceName);

    // List state
    const items = ref([]);
    const total = ref(0);
    const loading = ref(false);
    const error = ref(null);

    // Single item state
    const item = ref(null);

    // Query state
    const query = reactive({
        page: 1,
        perPage: 10,
        search: "",
        sort: "",
        ...initialQuery,
    });

    // Fetch list
    const fetchAll = async () => {
        loading.value = true;
        error.value = null;
        try {
            const params = {
                page: query.page,
                per_page: query.perPage,
                search: query.search,
                sort: query.sort,
            };
            if (query.search === "") delete params.search;
            if (query.sort === "") delete params.sort;
            const response = await resourceService.getAll(params);
            if (model) {
                items.value = response.data.data.map((item) => new model(item));
            } else {
                items.value = response.data.data || response.data;
            }
            total.value = response.data.meta?.total || response.data.total || 0;
        } catch (err) {
            error.value = err;
        } finally {
            loading.value = false;
        }
    };

    // Fetch one
    const fetchOne = async (id) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await resourceService.get(id);
            item.value = response.data;
        } catch (err) {
            error.value = err;
        } finally {
            loading.value = false;
        }
    };

    // Create
    const create = async (data) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await resourceService.create(data);
            await fetchAll(); // refresh list
            return response.data;
        } catch (err) {
            error.value = err;
            throw err;
        } finally {
            loading.value = false;
        }
    };

    // Update
    const update = async (id, data) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await resourceService.update(id, data);
            await fetchAll(); // refresh list
            return response.data;
        } catch (err) {
            error.value = err;
            throw err;
        } finally {
            loading.value = false;
        }
    };

    // Delete
    const remove = async (id) => {
        loading.value = true;
        error.value = null;
        try {
            await resourceService.delete(id);
            await fetchAll(); // refresh list
        } catch (err) {
            error.value = err;
            throw err;
        } finally {
            loading.value = false;
        }
    };

    // Watch for query changes and refetch
    watch(query, fetchAll, { deep: true });

    const refetch = () => fetchAll();

    return {
        items,
        item,
        total,
        loading,
        error,
        query,
        fetchAll,
        fetchOne,
        create,
        update,
        remove,
        refetch,
    };
}