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
        path: '/restaurants/view/:id',
        name: 'restaurant.view',
        component: () => import('@/pages/RestaurantManagement/View'),
    },
    {
        path: '/restaurants/create',
        name: 'restaurant.create',
        component: () => import('@/pages/RestaurantManagement/Create'),
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
        path: '/menus/:restaurantId/editor',
        name: 'menus.editor',
        component: () => import('@/pages/Menus/Editor.vue'),
        props: true,
    },
    {
        path: '/menu-categories',
        name: 'menu-categories',
        component: () => import('@/pages/MenuCategories'),
    },
    {
        path: '/menu-categories/create',
        name: 'menu-categories.create',
        component: () => import('@/pages/MenuCategories/Create'),
    },
    {
        path: '/menu-items',
        name: 'menu-items',
        component: () => import('@/pages/MenuItems'),
    },
    {
        path: '/menu-items/create',
        name: 'menu-items.create',
        component: () => import('@/pages/MenuItems/Create'),
    },
    {
        path: '/menu-items/view/:id',
        name: 'menu-items.view',
        component: () => import('@/pages/MenuItems/View.vue'),
        props: true,
    },
    {
        path: '/tags',
        name: 'tags',
        component: () => import('@/pages/Tags'),
    },
    {
        path: '/email/resend-verification',
        name: 'resend-verification',
        component: () => import('@/pages/auth/VerifyEmail/resend'),
    },
    {
        path: '/initial-setup',
        name: 'initial-setup',
        component: () => import('@/pages/InitialSetup'),
    },
]

export default routes;