
export default [
  {
    path: '/login',
    component: () => import('./../components/Login.vue'),
    meta: {
      title: 'Login'
    }
  },
  {
    path: '/',
    component: () => import('./../components/partials/Master.vue'),
    meta: {
      requiresAuth: true,
      title: 'Cashier'
    },
    childern: [

    ]
  }
]
