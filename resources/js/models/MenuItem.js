import BaseModel from "./BaseModel";

class MenuItem extends BaseModel {  

    constructor(data) {
        super(data);
        this._name = data.name;
        this._slug = data.slug;
        this._description = data.description;
        this._price = data.price;
        this._preparation_time = data.preparation_time;
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
}

export default MenuItem;
