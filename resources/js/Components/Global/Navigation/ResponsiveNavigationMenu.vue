<template>
  <div class="lg:hidden fixed top-0 w-full nav-mask border-b-2 border-gray-100">
    <div class="flex justify-between h-16 w-full bg-black nav-mask">
      <div class="flex">
        <!-- Logo -->
        <div class="shrink-0 flex items-center">
          <Link @click="navigateToStream">
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
         class="lg:hidden bg-gray-800 text-white fixed w-full h-full">
      <!-- Responsive Settings Options -->
      <!--   Fix Menu height e.g., h-[calc(h-100%-16rem)]      -->
      <div ref="scrollableDiv" class="pb-0 h-[calc(100vh)] overflow-y-auto hide-scrollbar" @scroll="handleScroll">
        <div class="px-4 bg-gray-800 border-b border-1 border-white w-full h-100%">

          <div class="flex justify-between pt-2">
            <div class="flex justify-start pb-3">
              <div v-if="$page.props.jetstream.managesProfilePhotos" class="mt-2 min-w-[2.5rem]">
                <Link @click="appSettingStore.closeNavDropdown()"
                      :href="route('profile.show')"
                      :active="route().current('profile.show')">
                  <img class="h-12 w-12 rounded-full object-cover border-1 border-gray-300"
                       :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">
                </Link>
              </div>
              <div class="mt-1 ml-3 w-full">
                <div class="font-medium text-base text-gray-100 w-full">
                  <Link @click="appSettingStore.closeNavDropdown()"
                        :href="route('profile.show')"
                        :active="route().current('profile.show')">
                    {{ $page.props.user.name }}
                  </Link>
                </div>
                <div class="font-medium text-sm text-gray-100 w-full">
                  <Link @click="appSettingStore.closeNavDropdown()"
                        :href="route('profile.show')"
                        :active="route().current('profile.show')">
                    {{ $page.props.user.email }}
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


        <div class="space-y-1 z-50 bg-gray-900 pb-20 border-b border-1 border-white">

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

          <div v-if="!userStore.isSubscriber && !userStore.isVip && !userStore.isAdmin"
               class="flex flex-row w-full justify-center py-4">
            <PublicNavLink
                @click="upgrade"
                :active="appSettingStore.currentPage === 'upgrade'"
                class="flex bg-indigo-700">
              CLICK HERE TO UPGRADE YOUR ACCOUNT
            </PublicNavLink>
          </div>

          <JetResponsiveNavLink
              v-if="userStore.isCreator"
              @click="appSettingStore.closeNavDropdown()"
              :href="route('dashboard')"
              :active="appSettingStore.currentPage === 'dashboard'">
            Dashboard
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              :href="route('stream')"
              :active="appSettingStore.currentPage === 'stream'">
            Stream
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              v-if="userStore.isVip"
              @click="appSettingStore.closeNavDropdown()"
              :href="route('library')"
              :active="appSettingStore.currentPage === 'library'">
            My Library
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              :href="route('schedule')"
              :active="appSettingStore.currentPage === 'schedule'">
            Schedule
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              :href="route('news.index')"
              :active="appSettingStore.currentPage === 'news'">
            News
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              :href="route('shows.index')"
              :active="appSettingStore.currentPage === 'shows'">
            Shows
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              v-if="userStore.isSubscriber || userStore.isVip || userStore.isCreator"
              @click="appSettingStore.closeNavDropdown()"
              :href="route('movies.index')"
              :active="appSettingStore.currentPage === 'movies'">
            Movies
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              :href="route('shop')"
              :active="appSettingStore.currentPage === 'shop'">
            Shop
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              @click="appSettingStore.closeNavDropdown()"
              :href="route('profile.show')"
              :active="route().current('profile.show')">
            Settings
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              v-if="userStore.hasAccount"
              @click="billingPortal">
            Account
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              v-if="userStore.isCreator"
              @click="appSettingStore.closeNavDropdown()"
              :href="route('training')"
              :active="appSettingStore.currentPage === 'training'">
            Training
          </JetResponsiveNavLink>

          <JetResponsiveNavLink
              v-if="userStore.isCreator"
              @click="appSettingStore.closeNavDropdown()"
              :href="route('videoupload')"
              :active="appSettingStore.currentPage === 'videoUpload'">
            Video Upload
          </JetResponsiveNavLink>

          <!-- Authentication -->
          <form method="POST" @submit.prevent="logout">
            <JetResponsiveNavLink as="button" class="border-t-0">
              Log Out
            </JetResponsiveNavLink>
          </form>


          <!--                &lt;!&ndash; Creator Only Links &ndash;&gt;-->
          <!--                <div v-if="userStore.isCreator">-->
          <!--                    <div class="border-t border-1 mt-3 border-gray-300 bg-indigo-900 block px-4 py-2 text-xs text-white">-->
          <!--                        Creator Only Links-->
          <!--                    </div>-->


          <!--                </div>-->

          <!-- Admin Only Links -->
          <div v-if="userStore.isAdmin">
            <div class="border-t border-1 mt-3 border-gray-300 bg-black block px-4 py-2 text-xs text-gray-400">
              Admin Only Links
            </div>

            <JetResponsiveNavLink
                @click="appSettingStore.closeNavDropdown()"
                :href="route('admin.settings')"
                :active="route().current('admin.settings')">
              Admin Settings
            </JetResponsiveNavLink>

            <JetResponsiveNavLink
                @click="appSettingStore.closeNavDropdown()"
                :href="route('admin.schedule')"
                :active="route().current('admin.schedule')">
              Schedule
            </JetResponsiveNavLink>

            <JetResponsiveNavLink
                @click="appSettingStore.closeNavDropdown()"
                :href="route('admin.channels')"
                :active="route().current('admin.channels')">
              Channels
            </JetResponsiveNavLink>

            <JetResponsiveNavLink
                @click="appSettingStore.closeNavDropdown()"
                :href="route('calculations')"
                :active="route().current('calculations')">
              Calculations
            </JetResponsiveNavLink>

            <JetResponsiveNavLink
                @click="appSettingStore.closeNavDropdown()"
                :href="route('mistServerApi')"
                :active="route().current('mistServerApi')">
              MistServer API
            </JetResponsiveNavLink>
          </div>

          <div class="flex flex-col w-full space-y-1 text-gray-600 text-sm pb-20">
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
import { Inertia } from "@inertiajs/inertia"
import { Link } from "@inertiajs/inertia-vue3"
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
  Inertia.post(route('logout'), {}, {
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
  Inertia.visit(`/stream`)
}

const upgrade = (() => {
  Inertia.visit('/upgrade')
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
