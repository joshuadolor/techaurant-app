const storage = {};
export default class Cache {
    static get(key) {
        return storage[key];
    }

    static set(key, value) {
        storage[key] = value;
    }

    static remove(key) {
        delete storage[key];
    }

    static clear() {
        storage = {};
    }

    static has(key) {
        return storage?.hasOwnProperty(key);
    }
}