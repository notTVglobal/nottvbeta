<template>
    <div>
    <div class="fixed mt-96 bottom-0 mr-12">
        <button @click.prevent="scrollTo('#scrollToMe')"
                class="fixed top-20 h-12 right-36 bg-blue-800 hover:bg-blue-600 w-56 hidden"
                :class="{ isMobile: userStore.isMobile }"
            >CLICK HERE TO SCROLL TO BOTTOM</button>
    </div>
            <div class="chatChrome w-full h-full pb-2 py-2 flex flex-col-reverse overflow-y-scroll overflow-x-clip break-words messages">
                <div id="scrollToMe"></div>
                <div id="messages" v-for="(message, index) in chatStore.messages" :key="index">
                    <message-item :id="message.id" :message="message" :time="time(message.created_at)"/>
                </div>
            </div>
    </div>
</template>

<script setup>
import MessageItem from "@/Components/Chat/Message"
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"
import dayjs from 'dayjs'
import relativeTime from "dayjs/plugin/relativeTime"
import { onUpdated } from "vue";

let chatStore = useChatStore()
let userStore = useUserStore()

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



function scrollTo(selector) {
    document.querySelector(selector).scrollIntoView({ behavior: 'smooth'});
}

onUpdated(() => {
    if (chatStore.messages != null) {
        document.getElementById(chatStore.messages[0].id).scrollIntoView({behavior: "smooth"})
    }

})

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

<style scoped>
.isMobile {
    left: 1rem;
    width: 10rem;
}
</style>
