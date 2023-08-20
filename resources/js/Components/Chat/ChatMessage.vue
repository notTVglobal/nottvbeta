<template>
    <div class="flex space-x-2 mb-2 pr-5 w-full align-self-center">
       <div class="min-w-[2rem]">
           <img v-if="message.user_profile_photo_path"
                :src="'/storage/' + message.user_profile_photo_path" class="rounded-full h-8 w-8 object-cover">
           <img v-if="!message.user_profile_photo_path"
                src="" class="rounded-full h-8 w-8 object-cover bg-gray-300">
       </div>
        <div class="flex flex-col bg-gray-600 rounded-l-xl rounded-r-xl px-2 pb-1 bg-opacity-50 break-words">
            <div :key=timeKey>
                <span class="text-xs font-semibold text-gray-100">{{  message.user_name }}</span>
<!--                <span class="text-xs text-gray-200"> &middot; {{ time }}</span>-->
            </div>
            <div><span class="text-white break-words">{{ message.message }}</span></div>
        </div>

    </div>

</template>

<script setup>
// import dayjs from 'dayjs';
import {reactive, render, watchEffect} from "vue";
import {useChatStore} from "@/Stores/ChatStore";

let chatStore = useChatStore()

// let relativeTime = require('dayjs/plugin/relativeTime')
// dayjs.extend(relativeTime)

let props = defineProps({
    message: Object,
    time: reactive(String),
})

let timeKey = 1;

watchEffect(() => {
    timeKey = timeKey + 1
})


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
