export const getRules = () => ({
    name: [
        { required: true, message: 'Name is required', trigger: 'blur' },
        { min: 2, message: 'Name must be at least 2 characters', trigger: 'blur' },
    ],
    description: [
        { max: 2000, message: 'Description too long', trigger: 'blur' },
    ],
});

export default getRules;


