import { ElNotification } from 'element-plus'

const defaultOptions = {
    duration: 5000,
    position: 'top-right'
}

let currentNotification = null;

function showNotification(type, options, replace = true) {
    // Close existing notification of the same type if replace is true
    if (replace && currentNotification) {
        currentNotification.close()
        currentNotification = null
    }

    const config = {
        ...defaultOptions,
        type
    }

    if (typeof options === 'string') {
        config.message = options
        config.title = type.charAt(0).toUpperCase() + type.slice(1)
    } else {
        config.message = options.message
        config.title = options.title || (type.charAt(0).toUpperCase() + type.slice(1))
        config.duration = options.duration || defaultOptions.duration
        config.position = options.position || defaultOptions.position
    }

    // Create new notification and store the instance
    const notificationInstance = ElNotification(config)
    currentNotification = notificationInstance

    // Clear the reference when notification auto-closes
    if (config.duration > 0) {
        setTimeout(() => {
            if (currentNotification === notificationInstance) {
                currentNotification = null
            }
        }, config.duration)
    }

    return notificationInstance
}

export const notify = {
    success(options, replace = true) {
        return showNotification('success', options, replace)
    },
    error(options, replace = true) {
        return showNotification('error', options, replace)
    },
    info(options, replace = true) {
        return showNotification('info', options, replace)
    },
    warning(options, replace = true) {
        return showNotification('warning', options, replace)
    },

    // Clear all notifications
    clearAll() {
        Object.keys(currentNotification).forEach(type => {
            if (currentNotification) {
                currentNotification.close()
                currentNotification = null
            }
        })
    },

    // Clear specific type
    clear() {
        if (currentNotification) {
            currentNotification.close()
            currentNotification = null
        }
    }
} 