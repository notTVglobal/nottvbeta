<template>
  <div class="flex space-x-2 mb-2 pr-5 w-full align-self-center">
    <div class="min-w-[2rem]">
      <img v-if="message.user_profile_photo_path"
           :src="'/storage/' + message.user_profile_photo_path" class="rounded-full h-8 w-8 object-cover">
      <img v-if="!message.user_profile_photo_path"
           src="" class="rounded-full h-8 w-8 object-cover bg-gray-300">
    </div>
    <div class="flex flex-col">
      <div :class="[pipMessageBgClass, messageBgClass]"
           class="flex flex-col rounded-l-xl rounded-r-xl px-2 pb-1 bg-opacity-50 break-words">
        <span class="text-xs font-semibold text-gray-100 pt-1">{{ message.user_name }}</span>
        <span class="text-white break-words" v-html="message.message"/>
      </div>
      <div class="text-xs text-gray-200 pl-2 opacity-60">{{ timeAgo }}</div>
    </div>

  </div>

</template>

<script setup>
import { computed } from "vue"
import { useTimeAgo } from '@vueuse/core'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useChatStore } from "@/Stores/ChatStore"

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const chatStore = useChatStore()

let props = defineProps({
  message: Object,
})

const timeAgo = useTimeAgo(props.message.created_at)

const messageBgClass = computed(() => {
  return appSettingStore.fullPage ? 'lg:bg-gray-800 lg:bg-opacity-60' : ''
});

const pipMessageBgClass = computed(() => {
  return appSettingStore.pipChatMode
      ? appSettingStore.chatMessageBgColor
      : appSettingStore.primaryChatMessageBgColor;
});
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

