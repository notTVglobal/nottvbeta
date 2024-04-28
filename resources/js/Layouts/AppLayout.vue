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
      <ImageLightboxModal v-if="appSettingStore.showImageLightboxModal"/>
      <DialogNotification v-if="user"/>
      <GeneralServiceNotification v-if="user"/>
      <OrangeFeedbackBox v-if="user && !appSettingStore.showNavDropdown && !ottStore.showOttContent"/>
      <ToastNotification/>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onBeforeMount, onBeforeUnmount, defineAsyncComponent, onMounted } from 'vue'

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

import ResponsiveNavigationMenu from '@/Components/Global/Navigation/ResponsiveNavigationMenu'
import NavigationMenu from '@/Components/Global/Navigation/NavigationMenu'

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

let isStreamPage = ref()
let showLogin = ref(false)
let reloadVideoMainPlayer = 0

let props = defineProps({
  user: Object,
})

onMounted(async () => {
  if (props.user) { // Checking if user is logged in based on page props
    await userStore.updateUserTimezone()
    await userStore.fetchUserData()
    userStore.videoSettings = props.user.videoSettings
    // console.log('get user data on AppLayout')
    // Call any other user-specific initialization functions here
  }
})

// listen for new firstPlayVideo
// this could change to stop listening
// in the future, or it can listen for
// schedule updates.

const firstPlayVideoEcho = Echo.channel('firstPlayVideo')
    firstPlayVideoEcho.subscribed(() => {
    }).listen('.changeFirstPlayVideo', (e) => {
      console.log('Broadcast notification, first play video changed to: ' + e.showName);

      // Check if 'skip_first_playback_video' is not enabled
      if (userStore.videoSettings.skip_first_playback_video !== 1) {
        // Define the source object correctly
        const source = {
          source: e.source,
          mediaType: 'mistStream',
          type: e.type,
          name: e.name,
        };

        // Assuming `loadNewVideo` might need the `source` as a parameter
        videoPlayerStore.loadNewVideo(source);
      }
    });



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

onMounted(() => {
  console.log('we are here.')
  nowPlayingStore.fetchFirstPlayData()
  ottStore.showContentForDemo()
})

onBeforeUnmount(() => {
  channelStore.viewerCount = 0
  disconnect()
  appSettingStore.removeResizeListener()
})


function setPage() {
  isStreamPage = appSettingStore.currentPage === 'stream'
}

function disconnect() {
  window.Echo.leave('channel.' + channelStore.currentChannelId)
  window.Echo.leave('firstPlayVideo');
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
