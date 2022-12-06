<template>
    <div class="pb-64">
        <button @click.prevent="scrollTo('#scrollToMe')" class="bottom-0 mr-32 h-12 bg-blue-800 hover:bg-blue-600 w-56 rounded-lg">CLICK HERE TO SCROLL TO BOTTOM</button>
        <div class="chatChrome w-full h-full pb-36 pt-5 bottom-36 flex flex-col-reverse overflow-y-scroll overflow-x-clip break-words messages">
            <div id="scrollToMe"></div>
            <div id="messages" v-for="(message, index) in chatStore.messages.slice()" :key="index">
                <message-item :message="message" :time="time(message.created_at)"/>
            </div>
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

// add a WatchEffect here to update the time stamps
// every few minutes.

function time(e) {
    let formattedTime = dayjs().to(dayjs(e));
    return formattedTime;
}

let chatStore = useChatStore()

function scrollTo(selector) {
    document.querySelector(selector).scrollIntoView({ behavior: 'smooth'});
}

</script>

<script>
export default {
    name: 'messages',
    data() {
        return { messages: [] };
    },
    mounted() {
        this.getMessages();
    },
    updated() {
        // whenever data changes and the component re-renders, this is called.
        this.$nextTick(() => this.scrollToEnd());
    },
    methods: {
        async getMessages () {
            // ...snip...
        },
        scrollToEnd: function () {
            // scroll to the start of the last message
            this.$el.scrollTop = this.$el.lastElementChild.offsetTop;
        }
    }
}
</script>
