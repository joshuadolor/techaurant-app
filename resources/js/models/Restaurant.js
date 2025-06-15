import BaseModel from "./BaseModel";

class Restaurant extends BaseModel {
    constructor(data) {
        super(data);
        this._name = data.name;
        this._address = data.address;
        this._phone = data.phone;
        this._email = data.email;
        this._website = data.website;
        this._logo = data.logo;
        this._is_active = !!data.is_active;
        this._is_verified = !!data.is_verified;
    }

    get name() {
        return this._name;
    }

    get address() {
        return this._address;
    }

    get phone() {
        return this._phone;
    }

    get email() {
        return this._email;
    }

    get website() {
        return this._website;
    }

    get logo() {
        return this._logo;
    }

    get isActive() {
        return this._is_active;
    }

    get isVerified() {
        return this._is_verified;
    }
}

export default Restaurant;