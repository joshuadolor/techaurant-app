import BaseModel from "./BaseModel";

// @TODO: create a color map for tags
class Tag extends BaseModel {
    constructor(data) {
        super(data);
        this._name = data.name;
    }

    get name() {
        return this._name;
    }
}

export default Tag;