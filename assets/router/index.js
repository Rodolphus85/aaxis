import List from '../views/list.vue'
import Edit from '../views/edit.vue'
import Batch from '../views/batch.vue'
import Login from '../views/login.vue'

import * as vueRouter from 'vue-router';

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login
  },
  {
    path: '/list',
    name: 'list',
    component: List
  },
  {
    path: '/edit/:sku',
    name: 'edit',
    component: Edit
  },
  {
    path: '/batch',
    name: 'batch',
    component: Batch
  },
]

const router = vueRouter.createRouter({
  history: vueRouter.createWebHistory(),
  routes,
});

export default router