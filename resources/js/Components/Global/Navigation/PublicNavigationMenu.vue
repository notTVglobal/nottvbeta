<template>
  <div class="hidden lg:block fixed top-0 w-full nav-mask">
    <nav class="sticky top-0 bg-black border-b border-gray-100 z-50">
      <!-- Primary Navigation Menu -->
      <div class="max-w-7xl mx-auto px-4 lg:px-6 xl:px-8 z-50">
        <div class="flex justify-between h-16">
          <div class="flex">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
              <Link :href="`/`">
                <JetApplicationMark class="block h-9 w-auto"/>
              </Link>
            </div>
            <div v-if="!$page.props.user" class="w-full flex flex-row justify-between">
              <div class="space-x-4 py-6 pt-6 ml-8 text-gray-200">
                <h3 class="inline-flex items-center relative">
                  <JetNavLink
                  ><Link :href="`/`">Watch Now</Link></JetNavLink>
                </h3>
<!--                <h3 class="inline-flex items-center relative">-->
<!--                  <JetNavLink-->
<!--                  ><Link :href="`/teams`">Browse</Link></JetNavLink>-->
<!--                </h3>-->

                <h3 class="inline-flex items-center relative">
                  <JetNavLink
                      :href="route('schedule')"
                      :active="route().current('schedule')">
                    Schedule</JetNavLink>
                </h3>

                <h3 class="hidden inline-flex items-center relative">
                  <JetNavLink
                      :href="route('teams.index')"
                      :active="route().current('teams.index')">
                    Browse</JetNavLink>
                </h3>

<!--                <h3 class="inline-flex items-center relative">-->
<!--                  <JetNavLink-->
<!--                  ><Link :href="`/schedule`">-->
<!--                    Schedule</Link></JetNavLink>-->
<!--                </h3>-->
                <h3 class="inline-flex items-center relative">
                  <JetNavLink
                      :href="route('news.index')"
                      :active="route().current('news.index')">
                    News Stories</JetNavLink>
                </h3>
                <h3 class="inline-flex items-center relative">
                  <JetNavLink
                      :href="route('news.reporters.index')"
                      :active="route().current('news.reporters.index')">
                    Reporters</JetNavLink>
                </h3>
                <h3 class="inline-flex items-center relative">
                  <JetNavLink
                      :href="route('public.newsletterSignup')"
                      :active="route().current('public.newsletterSignup')">
                    Get Exclusive Access!</JetNavLink>
                </h3>
                <h3 class="inline-flex items-center relative">
                  <JetNavLink
                      :href="route('public.contact')"
                      :active="route().current('public.contact')">
                    Contact Us</JetNavLink>
                </h3>
              </div>
            </div>
          </div>
          <div v-if="!$page.props.user" class="space-x-4 py-6 pt-6 mx-8 text-gray-200">
            <h3 class="inline-flex items-center relative">
              <JetNavLink
                  :href="`/login`"
                  :active="appSettingStore.currentPage === 'login'">
                Login</JetNavLink>
            </h3>
            <h3 class="inline-flex items-center relative">
              <JetNavLink
                  :href="`/register`"
                  :active="appSettingStore.currentPage === 'register'">
                Register</JetNavLink>
            </h3>

          </div>
          <div v-if="$page.props.user && !isVerificationNoticeRoute" class="space-x-4 py-6 pt-6 mx-8 text-gray-200">
            <h3 v-if="$page.props.user.isCreator" class="inline-flex items-center relative">
              <JetNavLink
                  :href="`/dashboard`">
                Dashboard</JetNavLink>
            </h3>
            <h3 class="inline-flex items-center relative">
              <JetNavLink
                  :href="`/stream`">
                Back to Stream</JetNavLink>
            </h3>

          </div>
        </div>

      </div>
    </nav>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { computed, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import JetNavLink from '@/Jetstream/NavLink'
import JetApplicationMark from '@/Jetstream/ApplicationMark'
import JetDropdownLink from '@/Jetstream/DropdownLink'
import JetDropdown from '@/Jetstream/Dropdown'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"

import { useChatStore } from "@/Stores/ChatStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useUserStore } from "@/Stores/UserStore"
import { useWelcomeStore } from "@/Stores/WelcomeStore"

const appSettingStore = useAppSettingStore()
const chat = useChatStore()
const videoPlayerStore = useVideoPlayerStore()
const streamStore = useStreamStore()
const userStore = useUserStore()
const welcomeStore = useWelcomeStore()

const page = usePage();

// streamStore.isLive(true)

// let props = defineProps({
//   user: Object,
// })

appSettingStore.pageReload = false

// Define the computed property
const isVerificationNoticeRoute = computed(() => {
  return page.props.currentPath === '/email/verify';
});

// const returnToWelcomePage = () => {
//   appSettingStore.pageReload = true
//   router.visit('/')
// }


// let isStreamPage = false
//
// function setPage() {
//     if (appSettingStore.currentPage = "stream") {
//         videoPlayerStore.currentPageIsStream = true;
//     } else
//         videoPlayerStore.currentPageIsStream = false;
// }

// setPage()

// onMounted(() => {
//   getUser()
// })
//
// function getUser() {
//   if (props.user) {
//     userStore.id = props.user.id
//     userStore.roleId = props.user.role_id
//     userStore.userIsAdmin = props.user.isAdmin
//   }
//   userStore.isSubscriber()
//   userStore.isCreator()
//   userStore.isVip()
//   userStore.isAdmin()
// }

</script>
<style>

.isFullPageCss {
  background: rgba(0, 0, 0, 0.8);
  /*background: yellow;*/
}

</style>
