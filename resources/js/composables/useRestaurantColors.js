import { computed } from 'vue';

export function useRestaurantColors(restaurantGetter) {
    const hexToRgb = (hex) => {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result
            ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16),
            }
            : { r: 0, g: 0, b: 0 };
    };

    const primaryColorWithAlpha = (alpha) => {
        const restaurant = typeof restaurantGetter === 'function' ? restaurantGetter() : restaurantGetter.value;
        const color = restaurant?.config?.primaryColor || '#3B82F6';
        const rgb = hexToRgb(color);
        return `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, ${alpha})`;
    };

    const secondaryColorWithAlpha = (alpha) => {
        const restaurant = typeof restaurantGetter === 'function' ? restaurantGetter() : restaurantGetter.value;
        const color = restaurant?.config?.secondaryColor || '#8B5CF6';
        const rgb = hexToRgb(color);
        return `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, ${alpha})`;
    };

    const darkenColor = (hex, percent) => {
        const rgb = hexToRgb(hex);
        return `rgb(${Math.round(rgb.r * (1 - percent))}, ${Math.round(
            rgb.g * (1 - percent)
        )}, ${Math.round(rgb.b * (1 - percent))})`;
    };

    return {
        hexToRgb,
        primaryColorWithAlpha,
        secondaryColorWithAlpha,
        darkenColor,
    };
} 