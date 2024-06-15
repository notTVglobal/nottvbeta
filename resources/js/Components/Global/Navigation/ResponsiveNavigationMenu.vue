<template>
  <div class="xl:hidden fixed top-0 w-full nav-mask border-b-2 border-gray-100">
    <div class="flex justify-between h-16 w-full bg-black nav-mask">
      <div class="flex">
        <!-- Logo -->
        <div class="shrink-0 flex items-center">
          <Link @click="navigateToStream" :href="`#`">
            <JetApplicationMark class="ml-5 block h-9 w-auto"/>
          </Link>
        </div>

        <!-- Hamburger -->
        <div class="fixed top-3 right-4 hamburgerMask">
          <div class="-mr-2 flex items-center">
            <div v-if="userStore.isCreator" class="text-yellow-600 uppercase hidden sm:block w-full">
              GOAL <span class="text-xl">100</span> subscribers
            </div>

            <div class="mx-6">
              <NotificationButton/>
            </div>

            <button
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-100 transition hamburgerMask"
                :class="{ 'hover:text-white hover:bg-blue-600': appSettingStore.showNavDropdown}"

                @click="appSettingStore.toggleNavDropdown()">
              <!--                @click="chatStore.showNavDropdown = ! chatStore.showNavDropdown">-->

              <span class="pr-2">MENU</span>
              <svg
                  class="h-6 w-6"
                  stroke="currentColor"
                  fill="none"
                  viewBox="0 0 24 24"
              >
                <path
                    :class="appSettingStore.showNavDropdown ? 'hidden' : 'inline-flex'"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                    :class="appSettingStore.showNavDropdown ? 'inline-flex' : 'hidden'"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>



      </div>
    </div>
    <!-- Responsive Navigation Menu -->
    <!--    <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}"-->
    <div :class="appSettingStore.showNavDropdown ? 'block' : 'hidden'"
         class="xl:hidden bg-gray-800 border-t text-white fixed w-full h-full">
      <!-- Responsive Settings Options -->
      <!--   Fix Menu height e.g., h-[calc(h-100%-16rem)]      -->
      <div ref="scrollableDiv" class="pb-0 h-[calc(100vh)] overflow-y-auto hide-scrollbar" @scroll="handleScroll">
        <div class="px-4 bg-gray-800 border-b border-1 border-white w-full h-100%">

          <div class="flex justify-between pt-2">
            <div class="flex justify-start pb-3">
              <div v-if="$page.props.jetstream.managesProfilePhotos" class="mt-2 min-w-[2.5rem]">
                <Link @click="appSettingStore.closeNavDropdown()"
                      @click.prevent="router.visit('/user/profile')"
                      :active="route().current('profile.show')"
                      :href="`#`">
                  <img class="min-h-12 min-w-12 max-h-12 max-w-12 mr-2 rounded-full object-cover border-1 border-gray-300"
                       :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                </Link>
              </div>
              <div class="mt-1 ml-3 w-full">
                <div class="font-medium text-base text-gray-100 w-full">
                  <Link @click="appSettingStore.closeNavDropdown()"
                        @click.prevent="router.visit('/user/profile')"
                        :active="route().current('profile.show')"
                        :href="`#`">
                    {{ $page.props.auth.user.name }}
                  </Link>
                </div>
                <div class="font-medium text-sm text-gray-100 w-full">
                  <Link @click="appSettingStore.closeNavDropdown()"
                        @click.prevent="router.visit('/user/profile')"
                        :active="route().current('profile.show')"
                        :href="`#`">
                    {{ $page.props.auth.user.email }}
                  </Link>
                </div>
              </div>
            </div>
            <div class="justify-end text-right w-36">

                <div v-if="userStore.isAdmin" class="text-xs font-semibold text-red-700">ADMIN</div>
                <div v-if="userStore.isSubscriber && !userStore.isCreator" class="text-xs font-semibold text-fuchsia-700">PREMIUM</div>
                <div v-if="userStore.isVip" class="text-xs font-semibold text-fuchsia-700">VIP</div>
              <div class="flex flex-row justify-end">
                <div v-if="userStore.isSubscriber && userStore.isCreator" class="text-xs font-semibold text-fuchsia-700">PREMIUM&nbsp;</div>
                <div v-if="userStore.isCreator" class="text-xs font-semibold text-fuchsia-700">CREATOR</div>
              </div>


            </div>

          </div>
        </div>


        <div class="space-y-1 z-50 bg-gray-900 pb-20 border-1 border-white">

<!--          <JetResponsiveNavLink-->
<!--              v-if="!userStore.isSubscriber && !userStore.isVip && !userStore.isAdmin"-->
<!--              @click="appSettingStore.closeNavDropdown()"-->
<!--              :href="route('upgrade')"-->
<!--              :active="appSettingStore.currentPage === 'upgrade'"-->
<!--          >-->
<!--            <div class="rounded-lg p-2 bg-gray-100 text-black hover:text-green-900">-->
<!--              CLICK HERE TO UPGRADE YOUR ACCOUNT-->
<!--            </div>-->
<!--          </JetResponsiveNavLink>-->

          <div
               class="flex flex-row w-full justify-center pt-8 pb-4">
            <PublicNavLink
                @click="contribute"
                :active="appSettingStore.currentPage === 'contribute'"
                class="flex bg-indigo-700">
              CONTRIBUTE
            </PublicNavLink>
          </div>

          <JetResponsiveNavLink
              v-if="userStore.isCreator"
              @click="appSettingStore.closeNavDropdown()"
              @click.prevent="router.visit('/dashboard')"
              :active="appSettingStore.currentPage === 'dashboard'">
            <font-awesome-icon :icon="['fas', 'tachometer-alt']" class="mr-2" /> Dashboard
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              @click.prevent="router.visit('/stream')"
              :active="appSettingStore.currentPage === 'stream'">
            <font-awesome-icon :icon="['fas', 'video']" class="mr-2" /> Stream
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              v-if="userStore.isVip"
              @click="appSettingStore.closeNavDropdown()"
              @click.prevent="router.visit('/library')"
              :active="appSettingStore.currentPage === 'library'">
            <font-awesome-icon :icon="['fas', 'book']" class="mr-2" /> My Library
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              @click.prevent="router.visit('/schedule')"
              :active="appSettingStore.currentPage === 'schedule'">
            <font-awesome-icon :icon="['fas', 'calendar-alt']" class="mr-2" /> Schedule
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              @click.prevent="router.visit('/browse')"
              :active="route().current('browse.index')">
            <font-awesome-icon :icon="['fas', 'users']" class="mr-2" /> Browse
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              @click.prevent="router.visit('/news')"
              :active="appSettingStore.currentPage.startsWith('news') && appSettingStore.currentPage !== 'newsroom'">
            <font-awesome-icon :icon="['fas', 'newspaper']" class="mr-2" /> News
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              v-if="userStore.isNewsPerson"
              @click="appSettingStore.closeNavDropdown()"
              @click.prevent="router.visit('/newsroom')"
              :active="appSettingStore.currentPage.startsWith('newsroom')">
            <font-awesome-icon :icon="['fas', 'building']" class="mr-2" /> Newsroom
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              @click.prevent="router.visit('/shows')"
              :active="appSettingStore.currentPage === 'shows'">
            <font-awesome-icon :icon="['fas', 'tv']" class="mr-2" /> Shows
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              v-if="userStore.isSubscriber || userStore.isVip || userStore.isCreator"
              @click="appSettingStore.closeNavDropdown()"
              @click.prevent="router.visit('/movies')"
              :active="appSettingStore.currentPage === 'movies'">
            <font-awesome-icon :icon="['fas', 'film']" class="mr-2" /> Movies
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              @click.prevent="router.visit('/shop')"
              :active="appSettingStore.currentPage === 'shop'"
              hidden>
            <font-awesome-icon :icon="['fas', 'shopping-cart']" class="mr-2" /> Shop
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              @click.prevent="router.visit('/user/profile')"
              :active="route().current('/user/profile')">
            <font-awesome-icon :icon="['fas', 'cog']" class="mr-2" /> Settings
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              v-if="$page.props.user.hasAccount"
              @click="billingPortal">
            <font-awesome-icon :icon="['fas', 'user-circle']" class="mr-2" /> Account
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              v-if="userStore.isCreator"
              @click="appSettingStore.closeNavDropdown()"
              @click.prevent="router.visit('/training')"
              :active="appSettingStore.currentPage === 'training'">
            <font-awesome-icon :icon="['fas', 'chalkboard-teacher']" class="mr-2" /> Training
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              v-if="userStore.isCreator"
              @click="appSettingStore.closeNavDropdown()"
              @click.prevent="router.visit('/videoupload')"
              :active="appSettingStore.currentPage === 'videoupload'">
            <font-awesome-icon :icon="['fas', 'upload']" class="mr-2" /> Video Upload
          </JetResponsiveNavLink>

          <!-- Authentication -->
          <JetResponsiveNavLink @click.prevent="logout" class="border-t-0">
            <font-awesome-icon :icon="['fas', 'sign-out-alt']" class="mr-2" /> Log Out
          </JetResponsiveNavLink>



          <!--                &lt;!&ndash; Creator Only Links &ndash;&gt;-->
          <!--                <div v-if="userStore.isCreator">-->
          <!--                    <div class="border-t border-1 mt-3 border-gray-300 bg-indigo-900 block px-4 py-2 text-xs text-white">-->
          <!--                        Creator Only Links-->
          <!--                    </div>-->


          <!--                </div>-->

          <!-- Admin Only Links -->
          <div v-if="userStore.isAdmin" class="bg-red-900">
            <div class="border-t border-1 mt-3 border-gray-300 bg-black block px-4 py-2 text-xs text-gray-400">
              Admin Only Links
            </div>

            <JetResponsiveNavLink
                @click="appSettingStore.closeNavDropdown()"
                @click.prevent="router.visit('/admin/settings')"
                :active="route().current('admin.settings')">
              <font-awesome-icon :icon="['fas', 'cog']" class="mr-2" /> Admin Settings
            </JetResponsiveNavLink>

            <JetResponsiveNavLink
                @click="appSettingStore.closeNavDropdown()"
                @click.prevent="router.visit('/admin/settings' + '?section=firstPlaySettings')">
              <font-awesome-icon :icon="['fas', 'play-circle']" class="mr-2" /> First Play Settings
            </JetResponsiveNavLink>

            <JetResponsiveNavLink
                @click="appSettingStore.closeNavDropdown()"
                @click.prevent="router.visit('/admin/schedule')"
                :active="route().current('admin.schedule')">
              <font-awesome-icon :icon="['fas', 'calendar-alt']" class="mr-2" /> Schedule
            </JetResponsiveNavLink>

            <JetResponsiveNavLink
                @click="appSettingStore.closeNavDropdown()"
                @click.prevent="router.visit('/admin/channels')"
                :active="route().current('admin.channels')">
              <font-awesome-icon :icon="['fas', 'tv']" class="mr-2" /> Channels
            </JetResponsiveNavLink>

            <JetResponsiveNavLink
                @click="appSettingStore.closeNavDropdown()"
                @click.prevent="router.visit('/calculations')"
                :active="route().current('calculations')">
              <font-awesome-icon :icon="['fas', 'calculator']" class="mr-2" /> Calculations
            </JetResponsiveNavLink>

            <JetResponsiveNavLink
                @click="appSettingStore.closeNavDropdown()"
                @click.prevent="router.visit('/admin/mistServerApi')"
                :active="route().current('mistServerApi')">
              <font-awesome-icon :icon="['fas', 'server']" class="mr-2" /> MistServer API
            </JetResponsiveNavLink>
          </div>

          <div class="flex flex-col w-full space-y-1 text-gray-600 text-sm py-10 bg-gray-950">
            <AppVersion/>
          </div>
        </div>
      </div>
      <div class="fixed w-full bottom-4 text-center fade-out"
           :class="{ 'visible': !hasScrolled && isContentOverflowing }">Scroll down.
      </div>
    </div>
  </div>
</template>

<script setup>
import { router, Link, usePage } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted, watch, nextTick } from "vue"
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"
import { useStoreReset } from "@/Utilities/StoreReset"
import JetApplicationMark from "@/Jetstream/ApplicationMark"
import AppVersion from "@/Components/Global/AppVersion/AppVersion"
import NotificationButton from "@/Components/Global/Notifications/NotificationButton"
import PublicNavLink from '@/Components/Global/Buttons/PublicNavLink.vue'
import { route } from "ziggy-js";
import JetNavLink from "@/Jetstream/NavLink.vue";

const page = usePage();

const showingNavigationDropdown = ref(false)

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const chatStore = useChatStore()
const userStore = useUserStore()
const storeReset = useStoreReset()

let props = defineProps({
  user: Object,
})

const hasScrolled = ref(false)
const isContentOverflowing = ref(false)
const scrollableDiv = ref(null)
//
// const checkOverflow = () => {
//     if (scrollableDiv.value) {
//         isContentOverflowing.value = scrollableDiv.value.scrollHeight > scrollableDiv.value.clientHeight;
//     }
// };

const checkOverflow = () => {
  if (scrollableDiv.value) {
    const isOverflowing = scrollableDiv.value.scrollHeight > scrollableDiv.value.clientHeight;
    isContentOverflowing.value = isOverflowing;
  }
};

const handleScroll = () => {
  // Check if the page has been scrolled down
  // const scrollPosition = scrollableDiv.value.scrollTop;
  // hasScrolled.value = scrollPosition > 0;
  hasScrolled.value = scrollableDiv.value.scrollTop > 0;
};

onMounted(() => {
  nextTick(() => {
    checkOverflow();
  });

  window.addEventListener('resize', checkOverflow); // Recheck on window resize

  if (scrollableDiv.value) {
    scrollableDiv.value.addEventListener('scroll', handleScroll);
  }
});

// Watch for changes in the dropdown visibility
watch(() => userStore.showNavDropdown, (newValue, oldValue) => {
  if (newValue !== oldValue) {
    nextTick(() => {
      checkOverflow();
    });
  }
});

// Cleanup
onUnmounted(() => {
  window.removeEventListener('resize', checkOverflow);

  if (scrollableDiv.value) {
    scrollableDiv.value.removeEventListener('scroll', handleScroll);
  }
});

const logout = () => {
  router.post(route('logout'), {}, {
    onSuccess: () => {
      // Reset state inside onSuccess callback
      storeReset.resetAllStores();
      window.location.reload(); // Force a page reload
    }
  });
};

// function loadStreamPage() {
//     videoPlayerStore.makeVideoFullPage()
//     appSettingStore.ott = 0
//     userStore.showNavDropdown = false
// }

function billingPortal() {
  location.href = ('https://not.tv/billing-portal')
}

function openNotifications() {
  document.getElementById('my_modal_3').showModal()
}

function navigateToStream() {
  videoPlayerStore.makeVideoFullPage()
  appSettingStore.ott = 0
  userStore.closeNavDropdown()
  if (!videoPlayerStore.currentPageIsStream) {
    userStore.prevUrl = window.history.state.url
  }
  router.visit(`/stream`)
}

const contribute = (() => {
  router.visit('/contribute')
  appSettingStore.closeNavDropdown()
})

</script>

<style scoped>
.fade-out {
  transition: opacity 0.5s ease-in-out;
  opacity: 0;
}

.fade-out.visible {
  opacity: 1;
}
</style>
