
export default [
  { path: '/', redirect: { name: 'cashier.selling' }},
  {
    path: '*',
    component: () => import('./../components/error/404.vue'),
    meta: {
      title: '404 Not Found'
    }
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('./../components/Login.vue'),
    meta: {
      title: 'Login'
    }
  },
  {
    path: '/cashier/',
    component: () => import('./../components/partials/Master.vue'),
    meta: {
      requiresAuth: true,
      title: 'Cashier'
    },
    children: [
      {
        path: 'selling',
        name: 'cashier.selling',
        component: () => import('./../components/Sell.vue'),
        meta: {
          requiresAuth: true,
          title: 'Selling'
        },
      },
      {
        path: 'selling/cart',
        name: 'cashier.selling.cart',
        component: () => import('./../components/Cart.vue'),
        meta: {
          requiresAuth: true,
          title: 'Selling Cart'
        },
      },
      {
        path: 'activity',
        name: 'cashier.activity',
        component: () => import('./../components/Activity.vue'),
        meta: {
          requiresAuth: true,
          title: 'Activity'
        },
      },
      {
        path: 'profile',
        name: 'cashier.profile',
        component: () => import('./../components/Profile.vue'),
        meta: {
          requiresAuth: true,
          title: 'Profile'
        },
      }
    ]
  }
]
