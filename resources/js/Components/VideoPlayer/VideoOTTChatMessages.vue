<template>
    <div class="pb-64 scrollbar-hide">
        <button @click.prevent="scrollTo('#scrollToMe')" class="bottom-0 mr-32 h-12 bg-blue-800 hover:bg-blue-600 w-56 rounded-lg hidden">CLICK HERE TO SCROLL TO BOTTOM</button>
        <div class="chatChrome w-full h-full pb-48 pt-5 bottom-48 flex flex-col-reverse overflow-y-scroll overflow-x-clip break-words messages scrollbar-hide">
            <div id="scrollToMe"></div>
            <div v-for="(message, index) in chatStore.messages.slice()" :key="index">
                <message-item :id="message.id" :message="message" :time="time(message.created_at)" />
            </div>
        </div>
    </div>
</template>

<script setup>
import MessageItem from "@/Components/Chat/Message"
import { useChatStore } from "@/Stores/ChatStore";
import dayjs from 'dayjs';
import relativeTime from "dayjs/plugin/relativeTime";
import { onUpdated } from "vue";
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

// get the newest message ID from the database.
// and

onUpdated(() => {
    if (chatStore.messages[0]) {
        document.getElementById(chatStore.messages[0].id).scrollIntoView({behavior: "smooth"})
    }
})


    //


// onMounted(() => {
//
// })


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
