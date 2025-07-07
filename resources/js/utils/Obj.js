/**
 * Safely gets a value from a nested object using a path string or array
 * Similar to Lodash's _.get function
 * @param {Object} obj - The object to query
 * @param {string|Array} path - The path to the property, can be dot notation string or array
 * @param {*} defaultValue - The value to return if path doesn't exist
 * @returns {*} - The value at path or defaultValue if not found
 */
export function get(obj, path, defaultValue = undefined) {
    // Handle null/undefined objects
    if (obj == null) {
        return defaultValue;
    }

    // Convert string path to array (e.g. "a.b.c" -> ["a", "b", "c"])
    const keys = Array.isArray(path) ? path : path.split('.');

    // Traverse the object
    let result = obj;
    for (const key of keys) {
        if (result == null || !Object.prototype.hasOwnProperty.call(result, key)) {
            return defaultValue;
        }
        result = result[key];
    }

    return result;
}

/**
 * Safely sets a value in a nested object using a path string or array
 * Similar to Lodash's _.set function
 * @param {Object} obj - The object to modify
 * @param {string|Array} path - The path to the property, can be dot notation string or array
 * @param {*} value - The value to set
 * @returns {Object} - The modified object
 */
export function set(obj, path, value) {
    if (obj == null) {
        return obj;
    }

    // Convert string path to array
    const keys = Array.isArray(path) ? path : path.split('.');

    let current = obj;
    const lastIndex = keys.length - 1;

    // Traverse and create nested objects as needed
    for (let i = 0; i < lastIndex; i++) {
        const key = keys[i];
        if (!(key in current)) {
            current[key] = {};
        }
        current = current[key];
    }

    // Set the final value
    current[keys[lastIndex]] = value;
    return obj;
}
