import { format } from "date-fns";

const defaultDateTimeFormat = "MMM d, yyyy HH:mm";

export const formatDate = (date, customFormat = defaultDateTimeFormat) => {
    if (!date) return "";
    if (typeof date === "string") {
        date = new Date(date);
    }

    return format(date, customFormat);
};