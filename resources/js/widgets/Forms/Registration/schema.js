const validatePasswordConfirmation = (rule, value, callback, form) => {
    if (value !== form.password) {
        callback(new Error('Passwords do not match'))
    } else {
        callback()
    }
}

export const getRules = (form) => ({
    name: [
        { required: true, message: 'Name is required', trigger: 'blur' },
    ],
    email: [
        { required: true, message: 'Email is required', trigger: 'blur' },
        { type: 'email', message: 'Please enter a valid email', trigger: 'blur' },
    ],
    password: [
        { required: true, message: 'Password is required', trigger: 'blur' },
        { min: 8, message: 'Password must be at least 8 characters', trigger: 'blur' }
    ],
    password_confirmation: [
        { required: true, message: 'Password confirmation is required', trigger: 'blur' },
        { validator: (rule, value, callback) => validatePasswordConfirmation(rule, value, callback, form), trigger: 'blur' }
    ]
})