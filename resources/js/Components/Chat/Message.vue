<template>
    <div class="flex space-x-2 my-2 pr-5 w-fit align-self-center">
       <div class="min-w-[2rem]">
           <img v-if="message.user_profile_photo_path"
                :src="'/storage/' + message.user_profile_photo_path" class="rounded-full h-8 w-8 object-cover">
           <img v-if="!message.user_profile_photo_path"
                src="" class="rounded-full h-8 w-8 object-cover bg-gray-300">
       </div>
        <div class="flex flex-col bg-gray-600 rounded-l-xl rounded-r-xl p-2 bg-opacity-50 break-words">
            <div><span class="text-xs font-semibold text-gray-100">{{  message.user_name }}</span><span class="text-xs text-gray-200"> &middot; {{ date }}</span></div>
            <div><span class="text-white break-words">{{ message.message }}</span></div>
        </div>

    </div>

</template>

<script setup>
import dayjs from 'dayjs';

let relativeTime = require('dayjs/plugin/relativeTime')
dayjs.extend(relativeTime)

let props = defineProps({
    message: Object,
})

function formatDate(dateString) {
    const date = dayjs().to(dayjs(dateString))
    // Then specify how you want your dates to be formatted
    return date;
}
let createdAt = props.message.created_at;

let date = formatDate(createdAt)

</script>
