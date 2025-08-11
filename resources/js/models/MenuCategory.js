import BaseModel from "./BaseModel";
import MenuItem from "./MenuItem";

class MenuCategory extends BaseModel {
    constructor(data) {
        super(data);
        this._name = data.name;
        this._slug = data.slug;
        this._description = data.description;
        this._menuItems = data.menu_items.map(item => new MenuItem(item));
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

    get menuItemsCount() {
        return this._menuItems.length;
    }
}

export default MenuCategory;