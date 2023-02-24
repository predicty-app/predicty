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
      },
      {
        path: '/onboarding/account-creation',
        name: 'on-boarding-account-creation',
        redirect: () => '/onboarding/account-creation/emial',
        component: () => import('@/views/AccountCreation/AccountCreationView.vue'),
        children: [
          {
            path: '/onboarding/account-creation/emial',
            name: 'on-boarding-account-creation-email',
            component: () => import('@/views/AccountCreation/AccountCreationEmailView.vue'),
          },
          {
            path: '/onboarding/account-creation/password',
            name: 'on-boarding-account-creation-password',
            component: () => import('@/views/AccountCreation/AccountCreationPasswordView.vue'),
          }
        ]
      },
      {
        path: '/onboarding/basic-media-integration',
        name: 'on-boarding-basic-media-integration',
        component: () => import('@/views/BasicMediaIntegrationView.vue'),
      },
      {
        path: '/onboarding/more-media-integration',
        name: 'on-boarding-more-media-integration',
        component: () => import('@/views/MoreMediaIntegrationView.vue'),
      },
      {
        path: '/onboarding/preparing-screen',
        name: 'on-boarding-preparing-screen',
        component: () => import('@/views/PreparingScreenView.vue'),
      }
    ]
  }
]


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
