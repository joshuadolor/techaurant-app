
const validatePasswordConfirmation = (rule, value, callback, form) => {
    if (value !== form.password) {
        callback(new Error('Passwords do not match'))
    } else {
        callback()
    }
}

export const getRules = (form) => ({
    password: [
        { required: true, message: 'Password is required', trigger: 'blur' },
        { min: 8, message: 'Password must be at least 8 characters', trigger: 'blur' }
    ],
    password_confirmation: [
        { required: true, message: 'Password confirmation is required', trigger: 'blur' },
        { validator: (rule, value, callback) => validatePasswordConfirmation(rule, value, callback, form), trigger: 'blur' }
    ],
    current_password: [
        { required: true, message: 'Current password is required', trigger: 'blur' }
    ]
})