<template>


  <div v-if="appSettingStore.noLayout" :class="[layoutClass, scrollbarClass]">
    <slot />
  </div>

  <div v-else>
    <div :class="appSettingStore.pageBgColor"
          class="fixed top-0 left-0 right-0 bottom-0 text-gray-200 vh-100 vw-100 overflow-hidden overscroll-y-none overscroll-x-none hide-scrollbar">
    "
    <!-- Navbar for logged in user -->
    <template v-if="user">
      <ResponsiveNavigationMenu :key="userStore.currentPage"/>
      <NavigationMenu :key="userStore.currentPage"/>
      <NotificationModal />
    </template>

    <!-- Login for Welcome Page (logged out) -->
    <Teleport to="body">
      <Login v-if="!user" :show="showLogin" @close="showLogin = false"/>
    </Teleport>

    <!-- Page Content -->
    <!--        <div v-show="!userStore.hidePage" :class="layoutClass">-->

      <div v-show="!appSettingStore.pageIsHidden" :class="[layoutClass, scrollbarClass]" class="">
        <slot/>
      </div>

      <!-- Video Player -->
      <VideoPlayerMain
          v-show="!appSettingStore.noLayout"
          :user="user"/>

      <DialogNotification/>
    </div>
  </div>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { ref, watch, provide, computed, onBeforeMount, onBeforeUnmount, defineAsyncComponent, onMounted } from "vue"
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useUserStore } from "@/Stores/UserStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useShopStore } from "@/Stores/ShopStore"
import { useChannelStore } from "@/Stores/ChannelStore"
import ResponsiveNavigationMenu from '@/Components/Global/Navigation/ResponsiveNavigationMenu'
import NavigationMenu from '@/Components/Global/Navigation/NavigationMenu'
import NotificationModal from "@/Components/Global/Notifications/NotificationModal"
import DialogNotification from "@/Components/Global/Modals/DialogNotification"

// const ResponsiveNavigationMenu = defineAsyncComponent( () =>
//     import('@/Components/Global/Navigation/ResponsiveNavigationMenu'))
// const NavigationMenu = defineAsyncComponent( () =>
//     import('@/Components/Global/Navigation/NavigationMenu'))
const Login = defineAsyncComponent(() =>
    import('@/Components/Pages/Welcome/Login'))
// const VideoPlayerMain = defineAsyncComponent( () =>
//     import('@/Components/Global/VideoPlayer/VideoPlayerMain'))
import VideoPlayerMain from "@/Components/Global/VideoPlayer/VideoPlayerMain"

const appSettingStore = useAppSettingStore();
const videoPlayerStore = useVideoPlayerStore()
const userStore = useUserStore()
const streamStore = useStreamStore()
const chatStore = useChatStore()
const shopStore = useShopStore()
const channelStore = useChannelStore()

let isStreamPage = ref()
let showLogin = ref(false)
let reloadNav = 0

let props = defineProps({
  user: Object,
});

const userTimezone = ref('');

const pageHide = computed(() => ({
  'hidden lg:block': appSettingStore.ott && userStore.isMobile
}))

const layoutClass = computed(() => ({
  layoutWelcome: !props.user || appSettingStore.noLayout,
  layoutLoggedIn: props.user && !appSettingStore.noLayout,
  'hidden lg:block': appSettingStore.ott && userStore.isMobile
}))

userStore.checkIsMobile()
userStore.showNavDropdown = false

setPage()

onBeforeMount(() => {
  getUserTimezone()
  if (props.user && !userStore.getUserDataCompleted) {
    updateUserStore()
    provide(/* key */ 'getUserData', /* value */ true)
  } else {
    provide(/* key */ 'getUserData', /* value */ false)
  }
})

onMounted(() => {
  console.log('we are here.')
})

onBeforeUnmount(() => {
  channelStore.viewerCount = 0
  disconnect();
});


function setPage() {
  isStreamPage = appSettingStore.currentPage === "stream";
}


async function updateUserStore() {
  try {
    const response = await axios.post('/getUserStoreData');
    // Update userStore based on response
    userStore.id = response.data.id;
    appSettingStore.loggedIn = true
    userStore.isAdmin = response.data.isAdmin
    userStore.isCreator = response.data.isCreator
    userStore.isNewsPerson = response.data.isNewsPerson
    userStore.isVip = response.data.isVip
    userStore.isSubscriber = response.data.isSubscriber
    userStore.hasAccount = response.data.hasAccount
    userStore.getUserDataCompleted = true
    console.log('get user data on AppLayout')
    await userStore.subscribeToUserNotifications(response.data.id)
    await updateUserTimezone();
    userStore.timezone = userTimezone.value;
    reloadNav++;
    if (userStore.isCreator) {
      userStore.prevUrl = '/dashboard';
    } else {
      userStore.prevUrl = '/stream';
    }
  } catch (error) {
    console.error(error);
  }
}

const getUserTimezone = () => {
  // Use the Intl object to get the user's timezone
  userTimezone.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
};

const updateUserTimezone = async () => {
  try {
    const response = await axios.post('/users/update-timezone', { timezone: userTimezone.value });
    console.log(response.data.message);
  } catch (error) {
    console.error(error.response ? error.response.data : error);
  }
};

// Watch for changes in userStore.currentPage
watch(() => userStore.getUserDataCompleted, (newPage, oldPage) => {
  // This function will be called whenever userStore.getUserDataCompleted changes
  reloadNav++
  console.log(`getUserDataCompleted changed from ${oldPage} to ${newPage}`);
});

function disconnect() {
  window.Echo.leave("channel." + channelStore.currentChannelId);
  console.log('CHANNEL DISCONNECTED');
}

const pageClass = computed(() => {
  return appSettingStore.pipChatMode
      ? appSettingStore.pipChatModeAppLayoutBgColor
      : appSettingStore.primaryAppLayoutBgColor;
});

const scrollbarClass = computed((() => {
  return appSettingStore.fullPage
      ? 'hide-scrollbar'
      : 'scrollbar-custom'
}))
</script>

<style scoped>


</style>
