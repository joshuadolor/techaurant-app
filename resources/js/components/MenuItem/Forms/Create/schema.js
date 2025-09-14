export const getRules = () => ({
    name: [
        { required: true, message: 'Name is required', trigger: 'blur' },
        { min: 2, message: 'Name must be at least 2 characters', trigger: 'blur' },
    ],
    slug: [
        { required: true, message: 'Slug is required', trigger: 'blur' },
    ],
    price: [
        { type: 'number', message: 'Price must be a number', trigger: 'blur' },
    ],
    preparation_time: [
        { type: 'number', message: 'Preparation time must be an integer', trigger: 'blur' },
    ],
});

export default getRules;


