export class ApiError extends Error {
    constructor(error) {
        super(error.message);
        this.response = error.response;
        this.status = error.response?.status;
    }
}

export class ValidationError extends Error {
    constructor(errors) {
        super('Validation failed');
        this.name = 'ValidationError';
        this.errors = errors;
    }

    getErrors() {
        return Object.entries(this.errors).reduce((acc, [key, value]) => {
            acc[key] = value[0];
            return acc;
        }, {});
    }
}

export class UnauthorizedError extends Error {
    constructor(message = 'Unauthorized') {
        super(message);
        this.name = 'UnauthorizedError';
    }
}

export class ServerError extends Error {
    constructor(message = 'Server error') {
        super(message);
        this.name = 'ServerError';
    }
}

export class UnverifiedUserError extends Error {
    constructor(message = 'Email not verified') {
        super(message);
        this.name = 'UnverifiedUserError';
    }
}

export class AccountLockedError extends Error {
    constructor(message = 'Account is locked') {
        super(message);
        this.name = 'AccountLockedError';
    }
}

export function handleApiError(error) {
    if (error.response) {
        const { data, status } = error.response;
        // Handle validation errors
        if (status === 422 && data.errors) {
            throw new ValidationError(data.errors);
        }

        // Handle unauthorized errors
        if (status === 401) {
            throw new UnauthorizedError(data.message);
        }

        // Handle server errors
        if (status >= 500) {
            throw new ServerError(data.message);
        }

        // Handle other errors
        throw new Error(data.message || 'An error occurred');
    }

    // Handle network errors
    if (error.request) {
        throw new Error('Network error occurred');
    }

    // Handle other errors
    throw error;
} 