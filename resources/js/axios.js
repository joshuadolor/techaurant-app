import axios from 'axios';

const instance = axios.create({
    baseURL: '/api',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
    },
    withCredentials: true
});

// Add request interceptor
instance.interceptors.request.use(config => {
    // Get the CSRF token from the meta tag
    const token = document.head.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (token) {
        config.headers['X-XSRF-TOKEN'] = token;
    }

    // Add auth token if available
    const authToken = localStorage.getItem('token');
    if (authToken) {
        config.headers.Authorization = `Bearer ${authToken}`;
    }

    return config;
}, error => {
    return Promise.reject(error);
});

// Add response interceptor
instance.interceptors.response.use(
    response => response,
    async error => {
        const originalRequest = error.config;
        const isLoginRequest = originalRequest.url.endsWith('/auth/login');

        // Only handle token refresh here
        if (error.response?.status === 401 && !originalRequest._retry && !isLoginRequest) {
            originalRequest._retry = true;

            try {
                const response = await axios.post('/api/auth/refresh', {}, {
                    withCredentials: true
                });

                const newToken = response.data.token;
                localStorage.setItem('token', newToken);
                originalRequest.headers.Authorization = `Bearer ${newToken}`;
                return instance(originalRequest);
            } catch (refreshError) {
                localStorage.removeItem('token');
                localStorage.removeItem('user');

                if (!window.location.pathname.includes('/login')) {
                    window.location.href = '/login';
                }
                throw refreshError;
            }
        }

        // Let ErrorHandler.js handle all other errors
        return Promise.reject(error);
    }
);

export default instance; 