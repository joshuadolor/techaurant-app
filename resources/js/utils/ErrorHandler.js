export class ApiError extends Error {
    constructor(error) {
        super(error.message);
        this.response = error.response;
        this.status = error.response?.status;
    }
}

export class ValidationError extends ApiError {
    constructor(error) {
        super(error);
        this.errors = error.response?.data?.errors || {};
    }
}

export class UnauthorizedError extends ApiError {
    constructor(error) {
        super(error);
        this.message = 'Your session has expired. Please log in again.';
    }
}

export class ServerError extends ApiError {
    constructor(error) {
        super(error);
        this.message = 'An unexpected error occurred. Please try again later.';
    }
}

export const createError = (error) => {
    const status = error.response?.status;

    switch (status) {
        case 422:
            return new ValidationError(error);
        case 401:
            return new UnauthorizedError(error);
        case 500:
            return new ServerError(error);
        default:
            return new ApiError(error);
    }
}; 