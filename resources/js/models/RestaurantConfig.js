import BaseModel from "./BaseModel";

class RestaurantConfig extends BaseModel {
    constructor(data) {
        super(data);
        this._logo_url = data.logo_url;
    }

    get logoUrl() {
        return this._logo_url;
    }
}

export default RestaurantConfig;