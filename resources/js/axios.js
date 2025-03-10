import axios from 'axios';

const instance = axios.create({
    baseURL: '/api',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
    },
    withCredentials: true
});

instance.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    } else {
        console.log('No token found');
    }
    return config;
}, error => {
    return Promise.reject(error);
});

// Add response interceptor
instance.interceptors.response.use(
    (response) => response,
    async (error) => {
        const originalRequest = error.config;

        // Check if error is 401 (Unauthorized) and we haven't tried refreshing yet
        if (error.response?.status === 401 && !originalRequest._retry) {
            originalRequest._retry = true;

            try {
                // Call your refresh token endpoint
                const response = await axios.post('/api/auth/refresh', {}, {
                    withCredentials: true
                });

                // Store the new token
                const newToken = response.data.token;
                localStorage.setItem('token', newToken);

                // Update the failed request with new token and retry
                originalRequest.headers.Authorization = `Bearer ${newToken}`;
                return instance(originalRequest);
            } catch (refreshError) {
                // If refresh fails, clear token and redirect to login
                localStorage.removeItem('token');
                window.location.href = '/login';
                return Promise.reject(refreshError);
            }
        }

        return Promise.reject(error);
    }
);

export default instance; 