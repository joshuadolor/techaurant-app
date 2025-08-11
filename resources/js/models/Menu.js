import BaseModel from "./BaseModel";
import MenuCategory from "./MenuCategory";

class Menu extends BaseModel {
    constructor(data) {
        super(data);
        this._name = data.name;
        this._slug = data.slug;
        this._description = data.description;
        this._categories = (data.categories || []).map(category => new MenuCategory(category));
        this._primary_image_url = data.primary_image_url;
        this._is_active = data.is_active;
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

    get categoriesCount() {
        return this._categories.length;
    }

    get categories() {
        return this._categories || [];
    }

    get itemsCount() {
        return this.categories.reduce((acc, category) => acc + category.menuItemsCount, 0);
    }

    get primaryImageUrl() {
        return this._primary_image_url;
    }

    get isActive() {
        return this._is_active;
    }

}

export default Menu;