import BaseModel from "./BaseModel";

class Menu extends BaseModel {
    constructor(data) {
        super(data);
        this._name = data.name;
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

    get owner() {
        return this._owner;
    }

    get primary_image_url() {
        return this._primary_image_url;
    }

    get is_active() {
        return this._is_active;
    }

    get owner_id() {
        return this._owner_id;
    }
}

export default Menu;