<template>
            <div class="w-full h-full py-2 flex flex-col-reverse overflow-y-scroll overflow-x-clip break-words">
                <div v-for="(message, index) in messages" :key="index">
                    <message-item :message="message"/>
                </div>
                Message Container
                <div ref="scrollToMe"></div>
            </div>
</template>

<script setup>
import MessageItem from "@/Components/Chat/Message"

defineProps({
    messages: Object,
})

</script>
