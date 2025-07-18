import BaseModel from './BaseModel.js';
import { formatTime24h, formatTime12h } from '@/utils/Date';

const capitalizeDayName = (day) => {
    return day.charAt(0).toUpperCase() + day.slice(1);
};

export default class RestaurantBusinessHour extends BaseModel {
    constructor(data) {
        super(data);
        this._dayOfWeek = capitalizeDayName(data.day_of_week);
        this._openTime = data.open_time;
        this._closeTime = data.close_time;
        this._isClosed = data.is_closed;
        this._rawData = data;
    }

    get rawData() {
        return this._rawData;
    }

    get isClosed() {
        return this._isClosed;
    }

    get isOpen() {
        return !this._isClosed;
    }

    get dayOfWeek() {
        return this._dayOfWeek;
    }

    get openTime() {
        return formatTime24h(this._openTime);
    }

    get closeTime() {
        return formatTime24h(this._closeTime);
    }

    get openTime12h() {
        return formatTime12h(this._openTime);
    }

    get closeTime12h() {
        return formatTime12h(this._closeTime);
    }

    get formattedSchedule() {
        if (this._isClosed) {
            return 'Closed';
        }

        const open = this.openTime;
        const close = this.closeTime;

        if (!open || !close) {
            return 'Schedule not set';
        }

        return `${open} - ${close}`;
    }

    get formattedSchedule12h() {
        if (this._isClosed) {
            return 'Closed';
        }

        const open = this.openTime12h;
        const close = this.closeTime12h;

        if (!open || !close) {
            return 'Schedule not set';
        }

        return `${open} - ${close}`;
    }

    get isValidSchedule() {
        if (this._isClosed) return true;
        return !!(this.openTime && this.closeTime);
    }
}