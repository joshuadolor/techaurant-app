import Login from '@/pages/auth/Login'
import Register from '@/pages/auth/Registration'

export const publicRoutes = [
    {
        path: '/',
        redirect: '/login',
        meta: { public: true }

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

]

const routes = [
    ...publicRoutes,
    {
        path: '/dashboard',
        name: 'dashboard',
        component: () => import('@/pages/Dashboard'),
    },
    {
        path: '/users',
        name: 'users',
        component: () => import('@/pages/UserManagement'),
    },
    {
        path: '/restaurants',
        name: 'restaurants',
        component: () => import('@/pages/RestaurantManagement'),
    },
    {
        path: '/account',
        name: 'account',
        component: () => import('@/pages/Account'),
    },
    {
        path: '/settings',
        name: 'settings',
        component: () => import('@/pages/Settings'),
    },
    {
        path: '/menus',
        name: 'menus',
        component: () => import('@/pages/Menus'),
    },
    {
        path: '/email/resend-verification',
        name: 'resend-verification',
        component: () => import('@/pages/auth/VerifyEmail/resend'),
    },
]

export default routes;