import { createRouter, createWebHistory } from 'vue-router'

const routes = [

  {
    path: '/onboarding/',
    name: 'on-boarding',
    component: () => import('@/views/OnBoardingView.vue'),
    redirect: () => '/onboarding/start-screen',
    children: [
      {
        path: '/onboarding/start-screen',
        name: 'on-boarding-start-screen',
        component: () => import('@/views/StartScreenView.vue'),
        props: {
          isProgressVisible: false,
        },
      },
      {
        path: '/onboarding/account-creation',
        name: 'on-boarding-account-creation',
        component: () => import('@/views/AccountCreationView.vue'),
        props: {
          isProgressVisible: true,
        },
      },
      {
        path: '/onboarding/basic-media-integration',
        name: 'on-boarding-basic-media-integration',
        component: () => import('@/views/BasicMediaIntegrationView.vue'),
        props: {
          isProgressVisible: true,
        },
      },
      {
        path: '/onboarding/more-media-integration',
        name: 'on-boarding-more-media-integration',
        component: () => import('@/views/MoreMediaIntegrationView.vue'),
        props: {
          isProgressVisible: true,
        },
      },
      {
        path: '/onboarding/preparing-screen',
        name: 'on-boarding-preparing-screen',
        component: () => import('@/views/PreparingScreenView.vue'),
        props: {
          isProgressVisible: false,
        },
      }
    ]
  }
]


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
