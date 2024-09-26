import { createRouter, createWebHistory } from 'vue-router'
import AppLayout from '@/components/AppLayout.vue'
import Dashboard from '../views/Dashboard.vue'
import Login from '../views/Login.vue'
import RequestPassword from '../views/RequestPassword.vue'
import ResetPassword from '../views/ResetPassword.vue'
import store from "../store";
import NotFound from '@/views/NotFound.vue'
import Product from '../views/Products/Product.vue'
import ProductForm from "../views/Products/ProductForm.vue";
const routes = [
  {
    path: '/',
    redirect: '/app'
  },
  {
    path: '/app',
    name: 'app',
    redirect: '/app/dashboard',
    component: AppLayout,
    meta: {
      requiresAuth: true
    },
    children: [
      {
        path: 'dashboard',
        name: 'app.dashboard',
        component: Dashboard ,

      },
      {
        path: 'products',
        name: 'app.products',
        component: Product,
      },
      {
        path: 'products/:id',
        name: 'app.products.edit',
        component: ProductForm,
      },
      {
        path: 'products/:id',
        name: 'app.products.delete',
        component: ProductForm,
      },
      {
        path: 'products/create',
        name: 'app.products.create',
        component: ProductForm,
      },
    ]
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      requiresGuest: true
    }
  },
  {
    path: '/request-password',
    name: 'requestPassword',
    component: RequestPassword,
    meta: {
      requiresGuest: true
    }
  },
  {
    path: '/reset-password/:token',
    name: 'resetPassword',
    component: ResetPassword,
    meta: {
      requiresGuest: true
    }
  },
  {
    path: '/:pathMatch(.*)',
    name: 'notfound',
    component: NotFound,
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
})


router.beforeEach((to, from, next) => {
  
  if (to.meta.requiresAuth && !store.state.user.token) {
    next({name: 'login'})
  } else if (to.meta.requiresGuest && store.state.user.token) {
    next({name: 'app.dashboard'})
  } else {
    next();
  }

})

export default router
