import { useOnBoardingStore } from "@/stores/onboarding";
import { useUserDashboardStore } from "@/stores/userDashboard";
import { createRouter, createWebHistory } from "vue-router";
import type { RouteRecordRaw } from "vue-router";
import GuardService, { TypeOfAction } from "@/services/guard";

const routes: Array<RouteRecordRaw> = [
  {
    path: "/authentication",
    name: "authentication",
    redirect: () => "/authentication/login",
    component: () => import("@/views/Authentication/AuthenticationView.vue"),
    children: [
      {
        path: "/authentication/login",
        name: "authentication-login",
        meta: {
          authentication: false,
          authorizationType: "dashboard"
        },
        component: () =>
          import("@/views/Authentication/AuthenticationLoginView.vue")
      },
      {
        path: "/authentication/reset-password",
        name: "authentication-reset-password",
        meta: {
          authentication: false,
          authorizationType: "dashboard"
        },
        component: () =>
          import("@/views/Authentication/AuthenticationResetPasswordView.vue")
      },
      {
        path: "/authentication/confirm-password-reset/:token",
        name: "authentication-confirm-password-reset",
        meta: {
          authentication: false,
          authorizationType: "dashboard"
        },
        component: () =>
          import(
            "@/views/Authentication/AuthenticationConfirmResetPasswordView.vue"
          ),
        beforeEnter: (route) => {
          if (!route.params.token) {
            return { path: "/authentication/login" };
          }
        }
      }
    ]
  },
  {
    path: "/onboarding/",
    name: "on-boarding",
    component: () => import("@/views/OnBoarding/OnBoardingView.vue"),
    redirect: () => "/onboarding/start-screen",
    children: [
      {
        path: "/onboarding/start-screen",
        name: "on-boarding-start-screen",
        meta: {
          authentication: false,
          authorizationType: "login"
        },
        component: () => import("@/views/OnBoarding/StartScreenView.vue")
      },
      {
        path: "/onboarding/account-creation",
        name: "on-boarding-account-creation",
        redirect: () => "/onboarding/account-creation/email",
        component: () =>
          import("@/views/OnBoarding/AccountCreation/AccountCreationView.vue"),
        children: [
          {
            path: "/onboarding/account-creation/email",
            name: "on-boarding-account-creation-email",
            meta: {
              authentication: false,
              authorizationType: "dashboard"
            },
            component: () =>
              import(
                "@/views/OnBoarding/AccountCreation/AccountCreationEmailView.vue"
              )
          },
          {
            path: "/onboarding/account-creation/password",
            name: "on-boarding-account-creation-password",
            component: () =>
              import(
                "@/views/OnBoarding/AccountCreation/AccountCreationPasswordView.vue"
              ),
            beforeEnter: () => {
              const onboardingStore = useOnBoardingStore();
              if (!onboardingStore.email) {
                return { path: "/onboarding/account-creation/email" };
              }
            }
          }
        ]
      },
      {
        path: "/onboarding/basic-media-integration",
        name: "on-boarding-basic-media-integration",
        meta: {
          authentication: true,
          authorizationType: "onboarding"
        },
        component: () =>
          import("@/views/OnBoarding/BasicMediaIntegrationView.vue"),
        beforeEnter: () => {
          const userDashboardStore = useUserDashboardStore();
          if (
            userDashboardStore.authenticatedUserParams &&
            userDashboardStore.authenticatedUserParams?.isOnboardingComplete
          ) {
            return { path: "/" };
          }
        }
      },
      {
        path: "/onboarding/more-media-integration",
        name: "on-boarding-more-media-integration",
        meta: {
          authentication: true
        },
        component: () =>
          import("@/views/OnBoarding/MoreMediaIntegrationView.vue"),
        beforeEnter: () => GuardService.checkIsOnboardingCompleted("/")
      },
      {
        path: "/onboarding/more-media-integration/file-settings",
        name: "on-boarding-more-media-integration-file-settings",
        meta: {
          authentication: true
        },
        component: () =>
          import("@/views/OnBoarding/MoreMediaIntegrationFileSettingsView.vue"),
        beforeEnter: () => GuardService.checkIsOnboardingCompleted("/")
      },
      {
        path: "/onboarding/preparing-screen",
        name: "on-boarding-preparing-screen",
        meta: {
          authentication: true
        },
        component: () => import("@/views/OnBoarding/PreparingScreenView.vue"),
        beforeEnter: () => GuardService.checkIsOnboardingCompleted("/")
      },
      {
        path: "/onboarding/preparing-screen/import-history",
        name: "on-boarding-preparing-screen-import-history",
        meta: {
          authentication: true
        },
        component: () =>
          import("@/views/OnBoarding/PreparingScreenImportHistoryView.vue"),
        beforeEnter: () => GuardService.checkIsOnboardingCompleted("/")
      }
    ]
  },
  {
    path: "/",
    name: "user-dashboard",
    component: () => import("@/views/UserDashboard/UserDashboardView.vue"),
    redirect: () => "/dashboard",
    beforeEnter: () =>
      GuardService.checkIsOnboardingCompleted(
        "/onboarding/basic-media-integration",
        TypeOfAction.NEGATION
      ),
    children: [
      {
        path: "/dashboard",
        name: "user-dashboard",
        meta: {
          authentication: true,
          authorizationType: "after"
        },
        component: () =>
          import("@/views/UserDashboard/UserDashboardPanelView.vue")
      },
      {
        path: "/dashboard/import-history",
        name: "import-history",
        meta: {
          authentication: true
        },
        component: () => import("@/views/UserDashboard/ImportHistoryView.vue")
      },
      {
        path: "/dashboard/upload-more-files",
        name: "upload-more-files",
        meta: {
          authentication: true
        },
        component: () => import("@/views/UserDashboard/UploadMoreFilesView.vue")
      }
    ]
  },
  {
    path: "/:pathMatch(.*)*",
    redirect: () => "/onboarding/start-screen"
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

GuardService.checkAuthentication(router);

export default router;
