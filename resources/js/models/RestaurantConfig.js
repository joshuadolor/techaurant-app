import BaseModel from "./BaseModel";

class RestaurantConfig extends BaseModel {
    constructor(data) {
        super(data);
        this._logo_url = data.logo_url;
        this._language = data.language;
        this._currency = data.currency;
        this._timezone = data.timezone;
        this._primaryColor = data.primary_color;
        this._secondaryColor = data.secondary_color;
    }

    get logoUrl() {
        return this._logo_url;
    }

    get languageCode() {
        return this._language;
    }

    get language() {
        return this.languageCode === "en" ? "English" : "Spanish";
    }

    get currency() {
        return this._currency;
    }

    get timezone() {
        return this._timezone;
    }

    get primaryColor() {
        return this._primaryColor;
    }

    get secondaryColor() {
        return this._secondaryColor;
    }
}

export default RestaurantConfig;