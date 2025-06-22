export const getRules = () => ({
    name: [
        {
            required: true,
            message: "Please enter restaurant name",
            trigger: "blur",
        },
        {
            min: 3,
            max: 50,
            message: "Length should be 3 to 50 characters",
            trigger: "blur",
        },
    ],
    tagline: [
        {
            max: 100,
            message: "Tagline should not exceed 100 characters",
            trigger: "blur",
        },
    ],
    "contact.email": [
        {
            required: true,
            message: "Please enter email address",
            trigger: "blur",
        },
        {
            type: "email",
            message: "Please enter a valid email address",
            trigger: "blur",
        },
    ],
    "contact.phone": [
        {
            required: true,
            message: "Please enter phone number",
            trigger: "blur",
        },
    ],
});