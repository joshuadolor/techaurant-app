/**
 * Convert a JSON object to FormData, handling nested objects and arrays
 * @param {Object} data - The JSON object to convert
 * @param {string} prefix - Optional prefix for nested keys
 * @returns {FormData} - The converted FormData object
 */
export function jsonToFormData(data, prefix = '') {
    const formData = new FormData();

    function appendValue(key, value) {
        const fullKey = prefix ? `${prefix}[${key}]` : key;

        if (value === null || value === undefined) {
            return;
        }

        if (value instanceof File) {
            formData.append(fullKey, value);
        } else if (typeof value === 'object' && !Array.isArray(value)) {
            // Handle nested objects
            Object.keys(value).forEach(subKey => {
                appendValue(subKey, value[subKey]);
            });
        } else if (Array.isArray(value)) {
            // Handle arrays
            value.forEach((item, index) => {
                if (typeof item === 'object' && item !== null) {
                    // Handle array of objects
                    Object.keys(item).forEach(subKey => {
                        appendValue(`${key}[${index}][${subKey}]`, item[subKey]);
                    });
                } else {
                    // Handle array of primitives
                    appendValue(`${key}[${index}]`, item);
                }
            });
        } else {
            // Handle primitives
            formData.append(fullKey, value);
        }
    }

    Object.keys(data).forEach(key => {
        appendValue(key, data[key]);
    });

    return formData;
}

/**
 * Convert FormData back to JSON object
 * @param {FormData} formData - The FormData to convert
 * @returns {Object} - The converted JSON object
 */
export function formDataToJson(formData) {
    const result = {};

    for (let [key, value] of formData.entries()) {
        const keys = key.match(/[^\[\]]+/g) || [key];
        let current = result;

        for (let i = 0; i < keys.length - 1; i++) {
            const k = keys[i];
            if (!(k in current)) {
                current[k] = {};
            }
            current = current[k];
        }

        const lastKey = keys[keys.length - 1];
        current[lastKey] = value;
    }

    return result;
}