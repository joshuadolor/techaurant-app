import BaseModel from "./BaseModel";
import RestaurantConfig from "./RestaurantConfig";

class Restaurant extends BaseModel {
    constructor(data) {
        super(data);
        this._name = data.name;
        this._tagline = data.tagline;
        this._address = data.address;
        this._phone = data.phone;
        this._email = data.email;
        this._website = data.website;
        this._logo = data.logo;
        this._is_active = !!data.is_active;
        this._is_verified = !!data.is_verified;
        this._config = new RestaurantConfig(data.config);
    }

    get name() {
        return this._name;
    }

    get tagline() {
        return this._tagline;
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

    get logoUrl() {
        return this._config.logoUrl;
    }
}

export default Restaurant;