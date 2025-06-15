class BaseModel {
    constructor(data) {
        this._uuid = data.uuid;
        this._rawData = data;
    }

    get id() {
        return this._uuid;
    }

    get rawData() {
        return this._rawData;
    }
}

export default BaseModel;