import * as yup from "yup";

const schema = yup.object().shape({
    email: yup
        .string()
        .required('Email is required')
        .email('Please enter a valid email'),
    password: yup
        .string()
        .required('Password is required')
        .min(8, 'Password must be at least 8 characters'),
    remember: yup.boolean().default(false)
});

export default schema;