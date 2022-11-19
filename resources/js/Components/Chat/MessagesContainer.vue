<template>
    <div class="fixed mt-96 bottom-0 mr-12">
        <button @click.prevent="scrollTo('#scrollToMe')" class="bottom-0 mr-32 h-12 bg-blue-800 hover:bg-blue-600 w-56">CLICK HERE TO SCROLL TO BOTTOM</button>
    </div>
            <div class="chatChrome w-full h-full py-2 flex flex-col-reverse overflow-y-scroll overflow-x-clip break-words messages">
                <div id="scrollToMe"></div>
                <div v-for="(message, index) in chatStore.messages" :key="index">
                    <message-item :message="message" :time="time(message.created_at)"/>
                </div>
            </div>
</template>

<script setup>
import MessageItem from "@/Components/Chat/Message"
import {useChatStore} from "@/Stores/ChatStore";
import dayjs from 'dayjs';
import relativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(relativeTime)

let props = defineProps({
    message: Object,
})

function time(e) {
    let formattedTime = dayjs().to(dayjs(e));
    return formattedTime;
}

let chatStore = useChatStore()

function scrollTo(selector) {
    document.querySelector(selector).scrollIntoView({ behavior: 'smooth'});
}

</script>
