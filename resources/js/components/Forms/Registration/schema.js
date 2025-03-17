import * as yup from "yup";

const schema = yup.object().shape({
    name: yup
        .string()
        .required('Name is required'),
    email: yup
        .string()
        .required('Email is required')
        .email('Please enter a valid email'),
    password: yup
        .string()
        .required('Password is required')
        .min(8, 'Password must be at least 8 characters'),
    passwordConfirmation: yup
        .string()
        .required('Password confirmation is required')
        .oneOf([yup.ref('password'), null], 'Passwords must match'),

});

export default schema;