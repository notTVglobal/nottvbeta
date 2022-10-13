<template>
    <div class="flex space-x-2 my-2 pr-5 w-fit">
       <div class="min-w-[2rem]">
           <img :src="'/storage/' + message.user.profile_photo_path" class="rounded-full h-8 w-8 object-cover">
       </div>
        <div class="flex flex-col bg-gray-600 rounded-l-xl rounded-r-xl p-2 bg-opacity-50 break-words">
            <span class="text-xs font-semibold text-gray-100">{{  message.user.name }}</span>
            <span class="text-white break-words">{{ message.message }}</span>
        </div>

    </div>

</template>

<script setup>

defineProps({
    message: Object,
})

</script>
