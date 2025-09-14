import BaseModel from "./BaseModel";

class MenuItem extends BaseModel {  

    constructor(data) {
        super(data);
        this._name = data.name;
        this._slug = data.slug;
        this._description = data.description;
        this._price = data.price;
        this._preparation_time = data.preparation_time;
        this._primary_image_url = data.primary_image_url;
        this._is_active = data.is_active;
        this._is_available = data.is_available;
        this._skus = data.skus;
    }

    get name() {
        return this._name;
    }

    get slug() {
        return this._slug;
    }

    get description() {
        return this._description;
    }

    get price() {
        return this._price;
    }

    get preparationTime() {
        return this._preparation_time;
    }

    get isCombo() {
        return this._is_combo;
    }

    get primaryImageUrl() {
        return this._primary_image_url;
    }

    get isActive() {
        return this._is_active;
    }

    get skus() {
        return this._skus || [];
    }
}

export default MenuItem;
