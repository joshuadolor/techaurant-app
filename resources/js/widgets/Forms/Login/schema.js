export const getRules = () => ({
    email: [
        { required: true, message: "Email is required", trigger: "blur" },
        {
            type: "email",
            message: "Please enter a valid email",
            trigger: "blur",
        },
    ],
    password: [
        { required: true, message: "Password is required", trigger: "blur" },
    ],
});
