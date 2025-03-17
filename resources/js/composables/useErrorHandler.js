import { ref } from 'vue';
import { ValidationError, UnauthorizedError, ServerError } from '@/utils/ErrorHandler';

export function useErrorHandler() {
    const validationErrors = ref({});
    const modalError = ref(null);
    const toastMessage = ref(null);

    const handleError = (error, { setValidationErrors } = {}) => {
        clearErrors();

        if (error instanceof ValidationError) {
            validationErrors.value = error.errors;
            setValidationErrors(validationErrors.value);
            return;
        }

        if (error instanceof UnauthorizedError) {
            modalError.value = {
                title: 'Authentication Error',
                message: error.message
            };
            return;
        }

        if (error instanceof ServerError) {
            modalError.value = {
                title: 'Server Error',
                message: error.message
            };
            return;
        }

        // Default error handling
        toastMessage.value = error.message;
    };

    const clearErrors = () => {
        validationErrors.value = {};
        modalError.value = null;
        toastMessage.value = null;
    };

    return {
        validationErrors,
        modalError,
        toastMessage,
        handleError,
        clearErrors
    };
} 