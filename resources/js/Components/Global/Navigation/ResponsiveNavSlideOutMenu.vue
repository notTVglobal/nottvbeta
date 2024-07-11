<template>
  <Transition
      enter-from-class="-translate-y-full -mt-16"
      enter-to-class="translate-y-0"
      enter-active-class="transition duration-300 ease-out"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-full"
  >
  <div v-if="appSettingStore.showNavDropdown" class="block xl:hidden bg-gray-800 border-t text-white fixed w-full h-full">
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
        <!--              :href="$route('upgrade')"-->
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

        <div ref="targetForScroll" class="flex flex-col w-full space-y-1 text-gray-600 text-sm py-10 bg-gray-950">
          <AppVersion/>
        </div>
      </div>
    </div>
    <div class="fixed w-full bottom-4 bg-black py-1 text-center fade-out"
         :class="{ 'visible': !hasScrolled }">Scroll down.
    </div>
  </div>
  </Transition>
</template>
<script setup>
import { Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useStoreReset } from '@/Utilities/StoreReset'
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'
import AppVersion from '@/Components/Global/AppVersion/AppVersion'
import { nextTick, onMounted, onUnmounted, ref, watch } from 'vue'
import PublicNavLink from '@/Components/Global/Buttons/PublicNavLink.vue'
import { useIntersectionObserver } from '@vueuse/core'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const storeReset = useStoreReset()

const hasScrolled = ref(false)
const scrollableDiv = ref(false)
const targetForScroll = ref(null)

useIntersectionObserver(
    targetForScroll.value,
    ([{isIntersecting}]) => {
      hasScrolled.value = !isIntersecting // Set isVisible to true when the target is intersecting
    },
)

function billingPortal() {
  location.href = ('https://not.tv/billing-portal')
}

function openNotifications() {
  document.getElementById('my_modal_3').showModal()
}

const contribute = (() => {
  router.visit('/contribute')
  appSettingStore.closeNavDropdown()
})

const handleScroll = () => {
  // Check if the page has been scrolled down
  // const scrollPosition = scrollableDiv.value.scrollTop;
  // hasScrolled.value = scrollPosition > 0;
  hasScrolled.value = scrollableDiv.value.scrollTop > 0;
};

const logout = () => {
  router.post(route('logout'), {}, {
    onSuccess: () => {
      // Reset state inside onSuccess callback
      storeReset.resetAllStores();
      window.location.reload(); // Force a page reload
    }
  });
};

// const checkOverflow = () => {
//   if (scrollableDiv.value) {
//     isContentOverflowing.value = scrollableDiv.value.scrollHeight > scrollableDiv.value.clientHeight;
//   }
// };

// // Watch for changes in the dropdown visibility
// watch(() => userStore.showNavDropdown, (newValue, oldValue) => {
//   if (newValue !== oldValue) {
//     nextTick(() => {
//       checkOverflow();
//     });
//   }
// });

onMounted(() => {
  // nextTick(() => {
  //   checkOverflow();
  // });

  // window.addEventListener('resize', checkOverflow); // Recheck on window resize

  if (scrollableDiv.value) {
    scrollableDiv.value.addEventListener('scroll', handleScroll);
  }
});

// Cleanup
onUnmounted(() => {
  // window.removeEventListener('resize', checkOverflow);

  if (scrollableDiv.value) {
    scrollableDiv.value.removeEventListener('scroll', handleScroll);
  }
});


</script>
<style scoped>
.fade-out {
  transition: opacity 0.5s ease-in-out;
  opacity: 0;
}

.fade-out.visible {
  opacity: 1;
}
.menu-animation {
  animation: fadeSlideUp 3s ease-in-out forwards;
}
</style>