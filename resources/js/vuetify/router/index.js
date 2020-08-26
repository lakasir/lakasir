
export default [
  {
    path: '*',
    component: () => import('./../components/error/404.vue'),
    meta: {
      title: '404 Not Found'
    }
  },
  {
    path: '/login',
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
          title: 'Cashier | List Item'
        },
      },
      {
        path: 'activity',
        name: 'cashier.activity',
        component: () => import('./../components/Activity.vue'),
        meta: {
          requiresAuth: true,
          title: 'Cashier | Activity'
        },
      },
      {
        path: 'profile',
        name: 'cashier.profile',
        component: () => import('./../components/Profile.vue'),
        meta: {
          requiresAuth: true,
          title: 'Cashier | Profile'
        },
      }
    ]
  }
]
