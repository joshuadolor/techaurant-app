import { ElNotification } from 'element-plus'

const defaultOptions = {
    duration: 3000,
    position: 'top-right'
}

export const notify = {
    success(options) {
        showNotification('success', options)
    },
    error(options) {
        showNotification('error', options)
    },
    info(options) {
        showNotification('info', options)
    },
    warning(options) {
        showNotification('warning', options)
    }
}

function showNotification(type, options) {
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

    ElNotification(config)
}

export default notify 