<template>
  <div class="videoOttTopRightButtonsContainer">
    <div class="flex flex-row h-7 justify-around bg-red-500 w-full text-xs uppercase font-semibold">

      <button class="h-full w-full border-r-2 border-r-gray-900"
              :class="infoClass"
              @click="appSettingStore.toggleOttInfo()">
        INFO
      </button>
      <button class="h-full w-full border-r-2 border-r-gray-900"
              :class="channelsClass"
              @click="appSettingStore.toggleOttChannels()">
        CHANNELS
      </button>
      <button class="h-full w-full border-r-2 border-r-gray-900"
              :class="playlistClass"
              @click="appSettingStore.toggleOttPlaylist()">
        PLAYLIST
      </button>
      <button class="h-full w-full border-r-2 border-r-gray-900"
              :class="chatClass"
              @click="appSettingStore.toggleOttChat()">
        CHAT
      </button>
      <button v-if="userStore.isVip || userStore.isAdmin"
              class="h-full w-full"
              :class="filtersClass"
              @click="appSettingStore.toggleOttFilters()">
        FILTERS
      </button>

    </div>
  </div>
</template>
<script setup>
import { computed } from "vue";
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useUserStore } from "@/Stores/UserStore"

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const userStore = useUserStore()

// Access level requirements
const accessLevels = {
  info: () => true, // All users
  channels: () => userStore.isSubscriber || userStore.isAdmin,
  playlist: () => userStore.isVip || userStore.isAdmin,
  chat: () => true, // All users
  filters: () => userStore.isVip || userStore.isAdmin,
};

// Function to determine the CSS class based on the access level and OTT setting
function getClassForSection(section, ottSetting) {
  const hasAccess = accessLevels[section]?.();
  const baseClass = 'bg-'; // Base class for the background color
  const colors = {
    info: 'purple',
    channels: hasAccess ? 'green' : 'gray',
    playlist: hasAccess ? 'orange' : 'gray',
    chat: 'indigo',
    filters: hasAccess ? 'yellow' : 'gray',
  };
  const color = colors[section];
  const isActive = appSettingStore.ott === ottSetting;
  return {
    [`${baseClass}${color}-700`]: isActive,
    [`${baseClass}${color}-900 hover:${baseClass}${color}-700`]: !isActive,
  };
}

// Using the function to set classes
const infoClass = computed(() => getClassForSection('info', 1));
const channelsClass = computed(() => getClassForSection('channels', 2));
// const playlistClass = computed(() => getClassForSection('playlist', 3));
const chatClass = computed(() => getClassForSection('chat', 4));
const filtersClass = computed(() => getClassForSection('filters', 5));

//
// const infoClass = computed(() => ({
//   // all users
//   'bg-purple-700': appSettingStore.ott === 1,
//   'bg-purple-900 hover:bg-purple-700': appSettingStore.ott !== 1,
// }))
//
// const channelsClass = computed(() => ({
//   // user is !subscriber
//   'bg-gray-700': appSettingStore.ott === 2 && userNeedsToUpgrade(),
//   'bg-gray-900 hover:bg-gray-700': appSettingStore.ott !== 2 && userNeedsToUpgrade(),
//   // user is subscriber
//   'bg-green-700': appSettingStore.ott === 2 && userIsSubscriber(),
//   'bg-green-900 hover:bg-green-700': appSettingStore.ott !== 2 && userIsSubscriber(),
// }))

const playlistClass = computed(() => ({
  // user is !subscriber
  'bg-gray-700': appSettingStore.ott === 3 && userNeedsToUpgrade(),
  'bg-gray-900 hover:bg-gray-700': appSettingStore.ott !== 3 && userNeedsToUpgrade(),
  // user is subscriber
  'bg-orange-700': appSettingStore.ott === 3 && userIsSubscriber(),
  'bg-orange-900 hover:bg-orange-700': appSettingStore.ott !== 3 && userIsSubscriber(),
}))
//
// const chatClass = computed(() => ({
//   // all users
//   'bg-indigo-700': appSettingStore.ott === 4,
//   'bg-indigo-900 hover:bg-indigo-700': appSettingStore.ott !== 4,
// }))
//
// const filtersClass = computed(() => ({
//   // user is !vip
//   'bg-gray-700': appSettingStore.ott === 5 && userNeedsToUpgrade(),
//   'bg-gray-900 hover:bg-gray-700': appSettingStore.ott !== 5 && userNeedsToUpgrade(),
//   // user is vip
//   'bg-yellow-700': appSettingStore.ott === 5 && userIsVip(),
//   'bg-yellow-900 hover:bg-yellow-700': appSettingStore.ott !== 5 && userIsVip(),
// }))
//
function userNeedsToUpgrade() {
  return !(userStore.isSubscriber || userStore.isVip || userStore.isAdmin);
}

function userIsSubscriber() {
  return (userStore.isSubscriber || userStore.isVip || userStore.isAdmin);
}

function userIsVip() {
  return (userStore.isVip || userStore.isAdmin);
}

</script>