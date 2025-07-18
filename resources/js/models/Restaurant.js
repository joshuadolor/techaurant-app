import BaseModel from "./BaseModel";
import RestaurantConfig from "./RestaurantConfig";
import { formatDate } from "@/utils/Date";
import RestaurantContact from "./RestaurantContact";
import RestaurantBusinessHour from "./RestaurantBusinessHour";

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
        this._contact = new RestaurantContact(data.contact || {});
        this._businessHours = data.business_hours.map(hour => new RestaurantBusinessHour(hour));
        this._description = data.description;
        this._created_at = data.created_at;
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

    get createdAt() {
        return formatDate(this._created_at);
    }

    get description() {
        return this._description;
    }

    get contact() {
        return this._contact;
    }

    get businessHours() {
        return this._businessHours;
    }

    get businessHoursByDay() {
        if (!this._businessHours) return {};

        // Define the order of days
        const dayOrder = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Group by day
        const grouped = this._businessHours.reduce((acc, hour) => {
            const dayName = hour.dayOfWeek;

            if (!acc[dayName]) {
                acc[dayName] = {
                    timePeriods: [],
                    data: hour
                };
            }

            acc[dayName].timePeriods.push({
                openTime: hour.openTime,
                closeTime: hour.closeTime,
                openTime12h: hour.openTime12h,
                closeTime12h: hour.closeTime12h,
            });

            return acc;
        }, {});

        // Ensure all days are present
        const result = {};
        dayOrder.forEach(day => {
            if (grouped[day]) {
                result[day] = grouped[day];
            } else {
                // Return default structure for days without data
                result[day] = {
                    timePeriods: [],
                    isClosed: true
                };
            }
        });

        return result;
    }

    // Get all open days
    get openDays() {
        return this.businessHoursByDay
            .filter(day => day.isOpen && day.timePeriods.length > 0)
            .map(day => day.dayName);
    }
}

export default Restaurant;