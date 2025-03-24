import Login from '@/pages/auth/Login'
import Register from '@/pages/auth/Registration'

const routes = [
    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { public: true }
    },
    {
        path: '/forgot-password',
        name: 'forgot-password',
        component: () => import('@/pages/auth/ForgotPassword'),
        meta: { public: true }
    },
    {
        path: '/reset-password',
        name: 'reset-password',
        component: () => import('@/pages/auth/ResetPassword'),
        meta: { public: true }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: { public: true }
    },
    {
        path: '/auth/:provider/callback',
        name: 'social-callback',
        component: () => import('@/pages/auth/callback'),
        meta: { public: true }
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: () => import('@/pages/Dashboard'),
    }
]

export default routes;