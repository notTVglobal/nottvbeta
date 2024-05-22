<template>
  <div class="hidden xl:block fixed top-0 w-full nav-mask border-b-2 border-gray-100">
    <nav class="sticky top-0 bg-black" :class="{ isFullPageCss: appSettingStore.fullPage }">
      <!-- Primary Navigation Menu -->
      <div class="mx-auto px-4 lg:px-6 xl:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
              <Link @click.prevent="appSettingStore.btnRedirect('/stream')" :href="`#`">
                <JetApplicationMark class="block h-9 w-auto"/>
              </Link>
            </div>

            <!-- Navigation Links -->
            <div class="hidden space-x-8 lg:-my-px lg:ml-10 lg:flex">
              <h3 class="inline-flex items-center relative">
                <JetNavLink
                    v-touch="()=>(router.visit('/stream'))"
                    @click.prevent="router.visit('/stream')"
                    :href="`#`"
                    :active="appSettingStore.currentPage === 'stream'">
                  Stream
                </JetNavLink>
              </h3>
              <h3 class="inline-flex items-center relative">
                <JetNavLink
                    v-touch="()=>(router.visit('/schedule'))"
                    @click.prevent="router.visit('/schedule')"
                    :href="`#`"
                    :active="appSettingStore.currentPage === 'schedule'">
                  Schedule
                </JetNavLink>
              </h3>
              <h3 class="inline-flex items-center relative">
                <JetNavLink
                    v-touch="()=>(router.visit('/teams'))"
                    @click.prevent="router.visit('/teams')"
                    :href="`#`"
                    :active="appSettingStore.currentPage === 'teams.index'">
                  Browse
                </JetNavLink>
              </h3>
              <h3 class="inline-flex items-center relative">
                <JetNavLink
                    v-touch="()=>(router.visit('/news'))"
                    @click.prevent="router.visit('/news')"
                    :href="`#`"
                    :active="appSettingStore.currentPage.startsWith('news') && appSettingStore.currentPage !== 'newsroom' && !appSettingStore.currentPage.startsWith('newsRss') && !appSettingStore.currentPage.startsWith('news-')">
                  News
                </JetNavLink>
              </h3>
              <h3 class="inline-flex items-center relative">
                <JetNavLink
                    v-touch="()=>(router.visit('/shows'))"
                    @click.prevent="router.visit('/shows')"
                    :href="`#`"
                    :active="appSettingStore.currentPage === 'shows'">
                  Shows
                </JetNavLink>
              </h3>
              <h3 class="inline-flex items-center relative"
                  v-if="userStore.isSubscriber || userStore.isVip || userStore.isAdmin">
                <JetNavLink
                    v-touch="()=>(router.visit('/movies'))"
                    @click.prevent="router.visit('/movies')"
                    :href="`#`"
                    :active="appSettingStore.currentPage === 'movies'">
                  Movies
                </JetNavLink>
              </h3>
              <h3 class="hidden items-center relative">
                <JetNavLink
                    v-touch="()=>(router.visit('/shop'))"
                    @click.prevent="router.visit('/shop')"
                    :href="`#`"
                    :active="appSettingStore.currentPage === 'shop'">
                  Shop
                </JetNavLink>
              </h3>
            </div>

          </div>


          <div class="flex flex-row space-x-4 pt-2">
            <div>
              <div v-if="userStore.isSubscriber && !userStore.isCreator" class="text-fuchsia-700">PREMIUM</div>
              <div v-if="userStore.isVip" class="text-fuchsia-700">VIP</div>
              <div v-if="userStore.isAdmin" class="text-red-700">ADMIN</div>
            </div>

            <div class="flex-item align-text-top mt-2 mx-6">
              <NotificationButton/>
            </div>

            <div v-if="userStore.isCreator"
                 class="flex-item align-self-center text-yellow-600 uppercase hidden md:block text-xs w-fit">
              <div class="flex flex-row">
                <div v-if="userStore.isSubscriber && userStore.isCreator" class="text-fuchsia-700">PREMIUM &nbsp;</div>
                <div class="text-fuchsia-700">CREATOR</div>
              </div>
              <div>GOAL <span class="text-sm font-semibold">100</span> subscribers</div>

            </div>
            <div class="flex-item">
              <div class="mt-2">
                <PublicNavLink
                    v-touch="()=>(router.visit('/contribute'))"
                    @click.prevent="router.visit('/contribute')"
                    :href="`#`"
                    :active="appSettingStore.currentPage === 'contribute'">
                  CONTRIBUTE
                </PublicNavLink>
<!--                />-->
<!--                <JetNavLink v-touch="()=>(route('upgrade'))"-->
<!--                            @click.prevent="videoPlayerStore.makeVideoTopRight()"-->
<!--                            :href="route('upgrade')"-->
<!--                            :active="appSettingStore.currentPage === 'upgrade'"-->
<!--                            class="active:border-none hover:border-none focus:border-none border-none">-->
<!--                  <div class="w-full rounded-lg p-2 bg-gray-100 text-black hover:bg-fuchsia-400 hover:text-green-900">-->
<!--                    UPGRADE NOW-->
<!--                  </div>-->
<!--                </JetNavLink>-->
              </div>
            </div>
            <!-- Settings Dropdown -->
            <div class="relative menuMask w-fit">

              <JetDropdown align="right" width="48">
                <template #trigger>
                  <button v-if="$page.props.jetstream.managesProfilePhotos"
                          class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                    <img class="h-12 w-12 rounded-full object-cover border-2 border-gray-600 hover:border-gray-300"
                         :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">
                  </button>

                  <span v-else class="inline-flex rounded-md">
                                            <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                                {{ $page.props.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path fill-rule="evenodd"
                                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                          clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </span>
                </template>

                <template #content>
                  <div class="divide-y">

                    <div class="text-xs text-gray-800 py-2 ml-4">Hello, {{ $page.props.user.name }}</div>

                    <div class="pt-2">

                      <JetDropdownLink
                          v-if="userStore.isCreator"
                          v-touch="()=>(router.visit('/dashboard'))"
                          @click.prevent="router.visit('/dashboard')"
                          :href="`#`"
                          :active="appSettingStore.currentPage === 'dashboard'">
                        Dashboard
                      </JetDropdownLink>

                      <JetDropdownLink
                          v-if="userStore.isNewsPerson"
                          v-touch="()=>(router.visit('/newsroom'))"
                          @click.prevent="router.visit('/newsroom')"
                          :href="`#`"
                          :active="appSettingStore.currentPage === 'newsroom'">
                        Newsroom
                      </JetDropdownLink>

                      <Link
                          v-if="userStore.isVip || userStore.isAdmin"
                          v-touch="()=>(router.visit('/library'))"
                          @click.prevent="router.visit('/library')"
                          :href="`#`"
                          :active="appSettingStore.currentPage === 'library'"
                          class="bg-gray-400 text-white block w-full px-4 py-2 text-sm leading-5 text-left">
                        My Library
                        <div class="text-xs text-white bg-yellow-800 uppercase flex justify-center items-center ml-1 -right-4 top-1.5
                                    font-semibold inline-block py-0.5 px-1 rounded last:mr-0 mr-1 shadow-cyan-950 drop-shadow-lg">
                          coming soon
                        </div>
                      </Link>

                      <JetDropdownLink
                          v-touch="()=>(router.visit('/user/profile'))"
                          @click.prevent="router.visit('/user/profile')"
                          :href="`#`"
                          :active="appSettingStore.currentPage === '/user/profile'">
                        Settings
                      </JetDropdownLink>

                      <JetDropdownLink
                          v-if="userStore.hasAccount"
                          @click.prevent="billingPortal"
                      >
                        Account
                      </JetDropdownLink>

                      <JetDropdownLink
                          v-if="userStore.isCreator"
                          v-touch="()=>(router.visit('/training'))"
                          :href="`#`"
                          @click.prevent="router.visit('/training')"
                          :active="appSettingStore.currentPage === 'training'">
                        Training
                      </JetDropdownLink>

                      <JetDropdownLink
                          v-if="userStore.isCreator"
                          v-touch="()=>(router.visit('/videoUpload'))"
                          :href="`#`"
                          @click.prevent="router.visit('/videoUpload')"
                          :active="appSettingStore.currentPage === 'videoUpload'">
                        Video Upload
                      </JetDropdownLink>

                      <!-- Authentication -->
                      <JetDropdownLink @click.prevent="logout">
                        Log Out
                      </JetDropdownLink>


                      <!--                                        &lt;!&ndash; Creator Only Links &ndash;&gt;-->
                      <!--                                        <div v-if="userStore.isCreator">-->
                      <!--                                            <div class="border-t border-1 mt-3 border-gray-300 block px-4 py-2 text-xs text-gray-400">-->
                      <!--                                                Creator Only Links-->
                      <!--                                            </div>-->

                      <!--                                        </div>-->

                      <!-- Admin Only Links -->
                      <div v-if="userStore.isAdmin" class="border-t border-b border-gray-300 bg-gray-700">
                        <div class="border-b border-1 border-gray-300 block px-4 py-2 text-xs text-gray-50 bg-gray-900">
                          Admin Only Links
                        </div>

                        <JetDropdownLink
                            v-touch="()=>(router.visit('/admin/settings'))"
                            @click.prevent="router.visit('/admin/settings')"
                            :href="`#`"
                            :active="appSettingStore.currentPage === 'admin.settings'"
                            :dark="true">
                          Admin Settings
                        </JetDropdownLink>

                        <JetDropdownLink
                            v-touch="()=>(router.visit('/admin/settings' + '?section=firstPlaySettings'))"
                            @click.prevent="router.visit('/admin/settings' + '?section=firstPlaySettings')"
                            :dark="true">
                          First Play Settings
                        </JetDropdownLink>

                        <JetDropdownLink
                            v-touch="()=>(router.visit('/admin/schedule'))"
                            @click.prevent="router.visit('/admin/schedule')"
                            :href="`#`"
                            :active="appSettingStore.currentPage === 'admin.schedule'"
                            :dark="true">
                          Schedule
                        </JetDropdownLink>

                        <JetDropdownLink
                            v-touch="()=>(router.visit('/admin/channels'))"
                            @click.prevent="router.visit('/admin/channels')"
                            :active="appSettingStore.currentPage === 'admin.channels'"
                            :dark="true">
                          Channels
                        </JetDropdownLink>

                        <JetDropdownLink
                            v-touch="()=>(router.visit('/calculations'))"
                            @click.prevent="router.visit('/calculations')"
                            :href="`#`"
                            :active="appSettingStore.currentPage === 'calculations'"
                            :dark="true">
                          Calculations
                        </JetDropdownLink>

                        <JetDropdownLink
                            v-touch="()=>(router.visit('/admin/mistServerApi'))"
                            @click.prevent="router.visit('/admin/mistServerApi')"
                            :href="`#`"
                            :active="appSettingStore.currentPage === 'mistServerApi'"
                            :dark="true">
                          MistServer API
                        </JetDropdownLink>

                      </div>

                    </div>
                    <div class="border-t border-gray-100">

                    </div>

                    <div class="grid grid-col-1 w-full text-gray-500 text-sm py-2">
                      <AppVersion/>
                    </div>

                  </div>
                </template>
              </JetDropdown>
            </div>
          </div>


        </div>
      </div>
    </nav>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import JetApplicationMark from '@/Jetstream/ApplicationMark'
import JetDropdownLink from '@/Jetstream/DropdownLink'
import JetDropdown from '@/Jetstream/Dropdown'
import JetNavLink from '@/Jetstream/NavLink'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useUserStore } from "@/Stores/UserStore"
import { useWelcomeStore } from "@/Stores/WelcomeStore"
import AppVersion from "@/Components/Global/AppVersion/AppVersion"
import NotificationButton from "@/Components/Global/Notifications/NotificationButton"
// import NotificationsButton from "@/Components/Global/Navigation/NotificationsButton"
import { useStoreReset } from "@/Utilities/StoreReset"
import PublicNavLink from '@/Components/Global/Buttons/PublicNavLink.vue'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const streamStore = useStreamStore()
const userStore = useUserStore()
const welcomeStore = useWelcomeStore()
const storeReset = useStoreReset()

let props = defineProps({
  user: Object,
})

// function loadStreamPage() {
//     videoPlayerStore.makeVideoFullPage()
//     userStore.showNavDropdown = false
// }

const logout = () => {
  router.post(route('logout'), {}, {
    onSuccess: () => {
      // Reset state inside onSuccess callback
      storeReset.resetAllStores()
      window.location.reload() // Force a page reload
    }
  })
}
// const navigateTest = (event) => {
//   event.preventDefault(); // Prevent the link's default navigation action
//   router.visit('/stream');
//   console.log('navigate TEST 11111')
// //   appSettingStore.btnRedirect('/stream')
// //   setTimeout(() => {
// //     router.visit('/stream');
// //   }, 1000); // Adjust timing as needed for testing
// // }
// // const navigateTest2 = () => {
// // console.log('navigate TEST 22222')
// //   setTimeout(() => {
// //     router.visit('/stream');
// //   }, 1000); // Adjust timing as needed for testing
// }


//     videoPlayerStore.mute();
//     appSettingStore.fullPage = true;
//     videoPlayerStore.loggedIn = false;
//     videoPlayerStore.ottChat = false;
//     appSettingStore.ott = 0;
//     videoPlayerStore.class = "videoBgFull";
//     videoPlayerStore.videoContainerClass = "videoContainerBgFull";
//     userStore.showNavDropdown = false;
//     userStore.clearUserData()
// };


// let isStreamPage = false
//
// function setPage() {
//     if (appSettingStore.currentPage = "stream") {
//         videoPlayerStore.currentPageIsStream = true;
//     } else
//         videoPlayerStore.currentPageIsStream = false;
// }

// setPage()

function billingPortal() {
  location.href = ('https://not.tv/billing-portal')
}

// function navigateToStream() {
//   videoPlayerStore.makeVideoFullPage()
//   appSettingStore.ott = 0
//   appSettingStore.showNavDropdown = false
//   userStore.prevUrl = window.history.state.url
//   router.visit(`/stream`)
// }

</script>
<style>

.isFullPageCss {
  background: rgba(0, 0, 0, 0.8)
  /*background: yellow;*/
}

</style>
