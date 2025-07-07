import { get, set } from "./Obj";
const storage = {};
export default class Cache {
    static get(key) {
        return get(storage, key);
    }

    static set(key, value) {
        set(storage, key, value);
    }

    static remove(key) {
        set(storage, key, undefined);
    }

    static clear() {
        Object.keys(storage).forEach((key) => {
            set(storage, key, undefined);
        });
    }

    static has(key) {
        return get(storage, key) !== undefined;
    }
}