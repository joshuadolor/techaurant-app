import { format, parse, isValid } from 'date-fns';

const defaultDateTimeFormat = "MMM d, yyyy HH:mm";

export const formatDate = (date, customFormat = defaultDateTimeFormat) => {
    if (!date) return "";
    if (typeof date === "string") {
        date = new Date(date);
    }

    if (date instanceof Date) {
        return format(date, customFormat);
    }

    return "";
};

/**
 * Format time string to 24h format (HH:mm) using date-fns
 * @param {string} timeString - Time string in various formats
 * @returns {string|null} - Formatted time string (HH:mm) or null if invalid
 */
export function formatTime24h(timeString) {
    if (!timeString || typeof timeString !== 'string') {
        return null;
    }

    timeString = timeString.trim();

    // If already in HH:mm format, validate and return
    if (timeString.match(/^\d{2}:\d{2}$/)) {
        const parsed = parse(timeString, 'HH:mm', new Date());
        return isValid(parsed) ? timeString : null;
    }

    // Try different parsing formats
    const formats = ['HH:mm:ss', 'H:mm:ss', 'H:mm', 'HH:mm'];
    const referenceDate = new Date(2000, 0, 1); // Use a reference date

    for (const formatStr of formats) {
        try {
            const parsed = parse(timeString, formatStr, referenceDate);
            if (isValid(parsed)) {
                return format(parsed, 'HH:mm');
            }
        } catch (error) {
            continue;
        }
    }

    return null;
}

/**
 * Format time string to display format with AM/PM using date-fns
 * @param {string} timeString - Time string in 24h format
 * @returns {string|null} - Formatted time string with AM/PM or null if invalid
 */
export function formatTime12h(timeString) {
    const time24h = formatTime24h(timeString);
    if (!time24h) return null;

    try {
        const parsed = parse(time24h, 'HH:mm', new Date());
        if (isValid(parsed)) {
            return format(parsed, 'h:mm a');
        }
    } catch (error) {
        return null;
    }

    return null;
}

/**
 * Check if a time string is valid using date-fns
 * @param {string} timeString - Time string to validate
 * @returns {boolean} - True if valid time format
 */
export function isValidTime(timeString) {
    return formatTime24h(timeString) !== null;
}

/**
 * Compare two time strings using date-fns
 * @param {string} time1 - First time string
 * @param {string} time2 - Second time string
 * @returns {number} - -1 if time1 < time2, 0 if equal, 1 if time1 > time2
 */
export function compareTime(time1, time2) {
    const t1 = formatTime24h(time1);
    const t2 = formatTime24h(time2);

    if (!t1 || !t2) return 0;

    try {
        const date1 = parse(t1, 'HH:mm', new Date());
        const date2 = parse(t2, 'HH:mm', new Date());

        if (date1 < date2) return -1;
        if (date1 > date2) return 1;
        return 0;
    } catch (error) {
        return 0;
    }
}

/**
 * Add minutes to a time string using date-fns
 * @param {string} timeString - Time string in HH:mm format
 * @param {number} minutes - Minutes to add
 * @returns {string|null} - New time string or null if invalid
 */
export function addMinutes(timeString, minutes) {
    const time24h = formatTime24h(timeString);
    if (!time24h) return null;

    try {
        const parsed = parse(time24h, 'HH:mm', new Date());
        if (isValid(parsed)) {
            const newTime = new Date(parsed.getTime() + minutes * 60000);
            return format(newTime, 'HH:mm');
        }
    } catch (error) {
        return null;
    }

    return null;
}