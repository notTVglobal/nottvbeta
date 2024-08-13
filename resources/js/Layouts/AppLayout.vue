<template>
  <div v-if="appSettingStore.noLayout" :class="[layoutClass, scrollbarClass]">
    <slot/>
  </div>

  <div v-else>

    <div :class="appSettingStore.pageBgColor"
         class="fixed top-0 left-0 right-0 bottom-0 text-gray-200 vh-100 vw-100 overflow-hidden overscroll-y-none overscroll-x-none hide-scrollbar">

      <!-- Navbar for logged in user -->
      <template v-if="user">
        <ResponsiveNavigationMenu/>
        <NavigationMenu/>
        <NotificationModal/>
      </template>

      <!-- Login for Welcome Page (logged out user) -->
      <Teleport to="body">
        <Login v-if="!user" :show="showLogin" @close="showLogin = false"/>
      </Teleport>

      <!-- Page Content -->
      <div v-show="!appSettingStore.pageIsHidden" :class="[layoutClass, scrollbarClass]">
        <slot/>
      </div>

      <!-- Video Player -->
      <OttContentFullPageDesktop/>
      <VideoPlayerMain
          :user="user"/>

      <!-- Notifications -->
      <!-- Popup Notification -->
      <AddWebAppPopupNotification
          v-if="showHomescreenPopup"
          :showPopup="showHomescreenPopup"
          @close="closePopup"
      />
      <CreatorWelcomeModal/>
      <CookieBanner/>
      <ImageLightboxModal v-if="appSettingStore.showImageLightboxModal"/>
      <DialogNotification v-if="user"/>
      <ConfirmNotificationModal v-if="user"/>
      <Teleport to="body">
        <GeneralServiceNotification v-if="user"/>
      </Teleport>
      <OrangeFeedbackBox
          v-if="user && !appSettingStore.showNavDropdown && !ottStore.showOttContent && appSettingStore.fullPage && appSettingStore.showOrangeFeedbackButton && appSettingStore.osd"/>
      <ToastNotification/>
      <SocialSharingModal v-if="socialShareStore.socialSharingModal"/>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onBeforeMount, onBeforeUnmount, defineAsyncComponent, onMounted, onUnmounted } from 'vue'

import { eventBus } from '@/Utilities/EventBus'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useWelcomeStore } from '@/Stores/WelcomeStore'
import { useNowPlayingStore } from '@/Stores/NowPlayingStore'
import { useStreamStore } from '@/Stores/StreamStore'
import { useUserStore } from '@/Stores/UserStore'
import { useChatStore } from '@/Stores/ChatStore'
import { useShopStore } from '@/Stores/ShopStore'
import { useChannelStore } from '@/Stores/ChannelStore'
import { useOttStore } from '@/Stores/OttStore'
import { useSocialShareStore } from '@/Stores/SocialShareStore'
// import { CreatorWelcomeModal } from '@/Components/Global/Modals/CreatorWelcomeModal.vue'

import ResponsiveNavigationMenu from '@/Components/Global/Navigation/ResponsiveNavigationMenu'
import NavigationMenu from '@/Components/Global/Navigation/NavigationMenu'
//
const CreatorWelcomeModal = defineAsyncComponent(() =>
    import('@/Components/Global/Modals/CreatorWelcomeModal.vue'))

const Login = defineAsyncComponent(() =>
    import('@/Components/Pages/Welcome/Login'))

import VideoPlayerMain from '@/Components/Global/VideoPlayer/VideoPlayerMain'
import OttContentFullPageDesktop from '@/Components/Global/Ott/Content/OttContentFullPageDesktop.vue'

import GeneralServiceNotification from '@/Components/Global/Modals/GeneralServiceNotification.vue'
import OrangeFeedbackBox from '@/Components/Global/Feedback/OrangeFeedbackBox.vue'
import ToastNotification from '@/Components/Global/Notifications/Toast/ToastNotification.vue'
import NotificationModal from '@/Components/Global/Notifications/NotificationModal'
import DialogNotification from '@/Components/Global/Modals/DialogNotification'
import ImageLightboxModal from '@/Components/Global/Modals/ImageLightboxModal.vue'
import CookieBanner from '@/Components/Global/Banners/CookieBanner.vue'
import ConfirmNotificationModal from '@/Components/Global/Modals/ConfirmNotificationModal.vue'
import SocialSharingModal from '@/Components/Global/Modals/SocialSharingModal.vue'
import useEchoListeners from '@/Utilities/EchoListeners'
import AddWebAppPopupNotification from '@/Components/Global/Popups/AddWebAppPopupNotification.vue'
import { router, usePage } from '@inertiajs/vue3'

const appSettingStore = useAppSettingStore()
const welcomeStore = useWelcomeStore()
const videoPlayerStore = useVideoPlayerStore()
const nowPlayingStore = useNowPlayingStore()
const userStore = useUserStore()
const streamStore = useStreamStore()
const chatStore = useChatStore()
const shopStore = useShopStore()
const channelStore = useChannelStore()
const ottStore = useOttStore()
const socialShareStore = useSocialShareStore()

let isStreamPage = ref()
let showLogin = ref(false)
let reloadVideoMainPlayer = 0

const page = usePage().props

let props = defineProps({
  user: Object,
})

// listen for new firstPlayVideo
// this could change to stop listening
// in the future, or it can listen for
// schedule updates.


const showHomescreenPopup = ref(page.showHomescreenPopup)

const closePopup = () => {
  showHomescreenPopup.value = false
  // Clear the session variable after showing the popup
  router.post('/clear-popup-session')
}

const firstPlayVideoEcho = Echo.channel('firstPlayVideo')

firstPlayVideoEcho.subscribed(() => {
  console.log('Successfully subscribed to firstPlayVideo channel')
}).listen('.changeFirstPlayVideo', (e) => {
  try {
    console.log('Received changeFirstPlayVideo event:', e)

    // Check if 'skip_first_playback_video' is not enabled
    if (userStore.videoSettings.skip_first_playback_video !== 1) {
      console.log('skip_first_playback_video is not enabled, proceeding with video update');

      const source = {
        source: e.firstPlayVideo.source,
        mediaType: e.firstPlayVideo.mediaType || 'mistStream', // Default to 'mistStream' if not specified
        type: e.firstPlayVideo.type,
        name: e.firstPlayVideo.name,
      }

      console.log('Source for new video:', source);

      videoPlayerStore.updateFirstPlay(source)
      // Load new video with the source data
      videoPlayerStore.loadNewFirstPlayVideo(source)
    } else {
      console.log('skip_first_playback_video is enabled, skipping video update');
    }
  } catch (error) {
      console.error('Error processing changeFirstPlayVideo event:', error);
    }
  }).error((error) => {
    console.error('Error with Echo channel:', error);
})

// const userTimezone = ref('');

const pageHide = computed(() => ({
  'hidden lg:block': appSettingStore.ott && userStore.isMobile,
}))

const layoutClass = computed(() => ({
  layoutWelcome: !props.user || appSettingStore.noLayout,
  layoutLoggedInVideoTopRight: props.user && !appSettingStore.noLayout && !appSettingStore.fullPage,
  layoutLoggedInVideoFullPage: props.user && !appSettingStore.noLayout && appSettingStore.fullPage,
  'hidden lg:block': appSettingStore.ott && userStore.isMobile,
}))

userStore.checkIsMobile()
userStore.showNavDropdown = false

setPage()

onBeforeMount(() => {
  appSettingStore.checkScreenSize()
})

onMounted(async () => {
  console.log('we are here.')
  useEchoListeners()
  await nowPlayingStore.fetchFirstPlayData()
  ottStore.showContentForDemo()
  if (props.user) { // Checking if user is logged in based on page props
    await userStore.updateUserTimezone()
    await userStore.fetchUserData()
    userStore.startTimer()
    userStore.videoSettings = props.user.videoSettings
    // console.log('get user data on AppLayout')
    // Call any other user-specific initialization functions here
  }
  eventBus.start(); // Start the global timer when the app mounts
})

onBeforeUnmount(() => {
  userStore.stopTimer()
  channelStore.viewerCount = 0
  disconnect()
  appSettingStore.removeResizeListener()
})

onUnmounted(() => {
  eventBus.stop(); // Stop the global timer when the app unmounts, if needed
})

function setPage() {
  isStreamPage = appSettingStore.currentPage === 'stream'
}


function disconnect() {
  window.Echo.leave('channel.' + channelStore.currentChannelId)
  window.Echo.leave('firstPlayVideo')
  console.log('CHANNEL DISCONNECTED')
}

const pageClass = computed(() => {
  return appSettingStore.pipChatMode
      ? appSettingStore.pipChatModeAppLayoutBgColor
      : appSettingStore.primaryAppLayoutBgColor
})

const scrollbarClass = computed((() => {
  return appSettingStore.fullPage
      ? 'hide-scrollbar'
      : 'scrollbar-custom'
}))
</script>

<style scoped>

</style>
