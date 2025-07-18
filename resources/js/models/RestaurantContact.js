import BaseModel from './BaseModel.js';
import { formatDate } from '@/utils/Date';

export default class RestaurantContact extends BaseModel {
    constructor(data) {
        super(data);

        this._phone = data.phone;
        this._email = data.email;
        this._address = data.address;
        // this._city = data.city;
        // this._state = data.state;
        // this._zip = data.zip;
        this._country_id = data.country_id;
        this._country = data.country;
        this._created_at = data.created_at;
        this._updated_at = data.updated_at;
    }

    get phone() {
        return this._phone;
    }

    get email() {
        return this._email;
    }

    // Getters for easy access
    get fullAddress() {
        const parts = [
            this.address,
            this.city,
            this.state,
            this.zip
        ].filter(part => part && part.trim());

        return parts.length > 0 ? parts.join(', ') : null;
    }

    get formattedPhone() {
        if (!this.phone) return null;

        // Format phone number with country code if available
        const countryCode = this.country?.phonecode;
        if (countryCode && !this.phone.startsWith('+')) {
            return `+${countryCode} ${this.phone}`;
        }

        return this.phone;
    }

    get countryName() {
        return this._country?.name || null;
    }

    get countryCode() {
        return this._country?.code || null;
    }

    get countryId() {
        return this._country?.id || null;
    }

    get hasCompleteAddress() {
        return !!(this.address && this.city && this.state && this.zip);
    }

    get hasContactInfo() {
        return !!(this.phone || this.email);
    }

    get displayAddress() {
        if (!this.rawAddress) return 'No address provided';

        const parts = [this.rawAddress];
        // if (this.city) parts.push(this.city);
        // if (this.state) parts.push(this.state);
        // if (this.zip) parts.push(this.zip);
        if (this.countryName) parts.push(this.countryName);

        return parts.join(', ');
    }

    // Validation methods
    isValidEmail() {
        if (!this.email) return false;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(this.email);
    }

    isValidPhone() {
        if (!this.phone) return false;
        // Basic phone validation - at least 7 digits
        const phoneRegex = /[\d\s\-\+\(\)]{7,}/;
        return phoneRegex.test(this.phone);
    }

    // Convert to form data format
    toFormData() {
        return {
            phone: this.phone || '',
            email: this.email || '',
            address: this.address || '',
            city: this.city || '',
            state: this.state || '',
            zip: this.zip || '',
            country_id: this.country_id || null
        };
    }

    get rawAddress() {
        return this._address;
    }

    get createdAt() {
        return formatDate(this._created_at);
    }

    // Convert to API format
    toApiData() {
        return {
            phone: this.phone || '',
            email: this.email || '',
            address: this.address || '',
            city: this.city || '',
            state: this.state || '',
            zip: this.zip || '',
            country_id: this.country_id
        };
    }
}