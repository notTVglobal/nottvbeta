<template>
  <div v-if="message.is_visible || isMessageFromCurrentUser"
       class="flex flex-row space-x-1 pb-3 pr-2 w-full align-self-center">
    <div class="min-w-[2rem]">
      <img v-if="message.user_profile_photo_path"
           :src="'/storage/' + message.user_profile_photo_path" class="rounded-full h-8 w-8 object-cover" alt="">
      <img v-if="!message.user_profile_photo_path"
           :src="message.user_profile_photo_url" class="rounded-full h-8 w-8 object-cover bg-gray-300" alt="">
    </div>
    <div class="flex flex-col min-w-[10rem]"
         @mouseover="showReactions"
         @mouseleave="hideReactions"
         @click="toggleReactionsVisibility">
      <div :class="[pipMessageBgClass, messageBgClass]"
           class="flex flex-col rounded-l-xl rounded-r-xl px-2 pb-1 bg-opacity-50"
>
        <div class="text-xs font-semibold text-gray-100 pt-1">{{ message.user_name }}</div>
        <div class="text-white text-sm" v-html="message.message"
             style="overflow-wrap: break-word; word-break: break-word;"></div>
      </div>
      <div class="flex justify-between items-center">
        <div class="text-xs text-gray-200 -mt-1 pl-2 opacity-80 drop-shadow">{{ timeAgo }}</div>
        <div class="relative flex items-center ml-4 space-x-2 min-h-[2rem]">
          <button
              v-if="heartCount > 0 || showHeart"
              @click="toggleReaction('heart', message.id)"
              class="relative flex items-center transition-transform duration-500 ease-in-out transform transition-opacity"
          >
            <span
                :class="[
                'transition-transform duration-300 ease-in-out',
                isHeartReaction ? 'scale-150 text-red-700' : 'text-red-500 hover:scale-125'
              ]"
            >
              ❤️
            </span>
            <span
                v-if="heartCount > 0"
                class="absolute text-[0.5rem] font-semibold border border-black text-white bg-red-500 rounded-full w-[0.9rem] h-[0.9rem] flex items-center justify-center"
                :style="{ top: '-0.7rem', right: '0rem' }"
            >
              {{ heartCount }}
            </span>
          </button>

          <button
              v-if="thumbsUpCount > 0 || showThumbsUp"
              @click="toggleReaction('thumbs_up', message.id)"
              class="relative flex items-center transition-transform duration-500 ease-in-out transform transition-opacity"
          >
            <span
                :class="[
                'transition-transform duration-300 ease-in-out',
                isThumbsUpReaction ? 'scale-150 text-blue-700' : 'text-blue-500 hover:scale-125'
              ]"
            >
              👍
            </span>
            <span
                v-if="thumbsUpCount > 0"
                class="absolute text-[0.5rem] font-semibold border border-black text-white bg-blue-500 rounded-full w-[0.9rem] h-[0.9rem] flex items-center justify-center"
                :style="{ top: '-0.7rem', right: '0rem' }"
            >
              {{ thumbsUpCount }}
            </span>
          </button>
        </div>
        <ShadowBanButton v-if="$page.props.auth.user.isAdmin && adminStore.shadowBanButton" :message="message"/>
      </div>
    </div>
  </div>
</template>



<script setup>
import { computed, ref } from 'vue'
import { useTimeAgo } from '@vueuse/core'
import { usePage } from '@inertiajs/vue3'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useAdminStore } from '@/Stores/AdminStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useChatStore } from '@/Stores/ChatStore'
import ShadowBanButton from '@/Components/Pages/Admin/Chat/ShadowBanButton.vue'

const appSettingStore = useAppSettingStore()
const adminStore = useAdminStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()
const chatStore = useChatStore()

const page = usePage()

let props = defineProps({
  message: Object,
})

const timeAgo = useTimeAgo(props.message.created_at)

const messageBgClass = computed(() => {
  return appSettingStore.fullPage ? 'lg:bg-gray-800 lg:bg-opacity-80' : ''
})

const pipMessageBgClass = computed(() => {
  return appSettingStore.pipChatMode
      ? appSettingStore.chatMessageBgColor
      : appSettingStore.primaryChatMessageBgColor
})

const isMessageFromCurrentUser = computed(() => {
  return page.props.auth.user.id === props.message.user_id
})

const userId = computed(() => userStore.user.id)

// Independent states for showing/hiding reactions
const showHeart = ref(false)
const showThumbsUp = ref(false)

// Ensure reactions is an array and handle undefined cases
const reactions = computed(() => props.message.reactions || [])

// Safely check if the current user has reacted with the specific reaction type
const isHeartReaction = computed(() => {
  return reactions.value.some(r => r.user_id === userId.value && r.reaction_type === 'heart')
})

const isThumbsUpReaction = computed(() => {
  return reactions.value.some(r => r.user_id === userId.value && r.reaction_type === 'thumbs_up')
})

// Safely compute reaction counts using a fallback for undefined reactions
const heartCount = computed(() => {
  return reactions.value.filter(r => r.reaction_type === 'heart').length
})

const thumbsUpCount = computed(() => {
  return reactions.value.filter(r => r.reaction_type === 'thumbs_up').length
})

// Show reactions when hovering over the message
const showReactions = () => {
  if (heartCount.value === 0) showHeart.value = true
  if (thumbsUpCount.value === 0) showThumbsUp.value = true
}

// Hide reactions when the mouse leaves and the count is 0
const hideReactions = () => {
  if (heartCount.value === 0) showHeart.value = false
  if (thumbsUpCount.value === 0) showThumbsUp.value = false
}

// Toggle reactions visibility on click
const toggleReactionsVisibility = () => {
  showHeart.value = !showHeart.value
  showThumbsUp.value = !showThumbsUp.value
}

const toggleReaction = (reactionType, messageId) => {
  chatStore.toggleReaction(reactionType, messageId);

  // Add a delay before checking the count and hiding the button if it's 0
  setTimeout(() => {
    if (reactionType === 'heart') {
      if (heartCount.value === 0) {
        showHeart.value = false;
      }
    } else if (reactionType === 'thumbs_up') {
      if (thumbsUpCount.value === 0) {
        showThumbsUp.value = false;
      }
    }
  }, 500); // 500 milliseconds delay (adjust as needed)
};


// // Directly check if the current user has reacted with the specific reaction type
// const isHeartReaction = computed(() => {
//   return props.message.reactions.some(r => {
//     // console.log('Checking reaction:', r);
//     return r.user_id === userId && r.reaction_type === 'heart';
//   });
// });
//
// const isThumbsUpReaction = computed(() => {
//   return props.message.reactions.some(r => {
//     // console.log('Checking reaction:', r);
//     return r.user_id === userId && r.reaction_type === 'thumbs_up';
//   });
// });
//
// // Computed properties to get the reaction counts
// const heartCount = computed(() => chatStore.heartCount(props.message.id));
// const thumbsUpCount = computed(() => chatStore.thumbsUpCount(props.message.id));
//
// // Method to toggle reactions
// const toggleReaction = (reactionType, messageId) => {
//   chatStore.toggleReaction(reactionType, messageId)
// }

</script>
<script>
// import dayjs from 'dayjs';
// let relativeTime = require('dayjs/plugin/relativeTime')
// dayjs.extend(relativeTime)
// const nowTime = current.getHours() + ":" + current.getMinutes() + ":" + current.getSeconds();
// let timeAgo =

// onUnmounted( () => {
//     timeAgo = ''
// })
//
// function formatDate(dateString) {
//     const date = dayjs().to(dayjs(dateString))
//     // Then specify how you want your dates to be formatted
//     return date;
// }
// let createdAt = props.message.created_at;
//
// let date = formatDate(createdAt)
</script>

