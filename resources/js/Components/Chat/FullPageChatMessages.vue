<template>
    <div>
<!--        <div class="fixed mt-96 bottom-0 mr-12">-->
<!--            <button @click.prevent="scrollTo('#scrollToMe')"-->
<!--                    class="fixed top-20 h-12 right-36 bg-blue-800 hover:bg-blue-600 w-56 hidden"-->
<!--                    :class="{ isMobile: userStore.isMobile }"-->
<!--                >CLICK HERE TO SCROLL TO BOTTOM</button>-->
<!--        </div>-->
        <div class="chatChrome w-full h-full pb-2 py-2 flex flex-col-reverse overflow-y-scroll overflow-x-clip break-words messages scrollbar-hide">
            <div id="scrollToMe"></div>

            <div id="newMessages" v-for="(message, messages) in chatStore.newMessages.slice().reverse()" :key="messages">
                <message-item :id="message.id" :message="message" :time="time(message.created_at)"/>
            </div>

            <div id="oldMessages" v-for="(message, messages) in chatStore.oldMessages.slice()" :key="messages">
                <message-item :id="message.id" :message="message" :time="time(message.created_at)"/>
            </div>

        </div>
    </div>
</template>

<script setup>
import MessageItem from "@/Components/Chat/ChatMessage.vue"
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"
import dayjs from 'dayjs'
import relativeTime from "dayjs/plugin/relativeTime"
import { onBeforeMount, onBeforeUnmount, onUpdated, ref } from "vue";

let chatStore = useChatStore()
let userStore = useUserStore()

dayjs.extend(relativeTime)

let props = defineProps({
    message: Object,
})

let channels = ref([])

onBeforeMount(async() => {
    await connect();
});

function connect() {
    console.log('STREAM CHAT CONNECTED');
    getChannels();
}

function getChannels() {
    axios.get('/chat/channels')
        .then(response => {
            channels = response;
            setChannel(channels.data[0]);
        })
        .catch(error => {
            console.log(error);
        })
}

function setChannel ( channel ){
    chatStore.currentChannel = channel;
    console.log('Joined Chat Channel: ' + chatStore.currentChannel.name);
    getMessages();
}

function getMessages() {
    axios.get('/chat/channel/' + chatStore.currentChannel.id + '/messages')
        .then( response => {
            chatStore.oldMessages = response.data;
        })
        .catch(error => {
            console.log(error);
        })
    console.log('LOAD MESSAGES');
}

function disconnect() {
    window.Echo.leave("chat." + chatStore.currentChannel.id );
    // chatStore.oldMessages = null;

    console.log('STREAM CHAT DISCONNECTED');
}

onBeforeUnmount(() => {
    chatStore.newMessages = [];
    disconnect();
});

const channel = Echo.private('chat.' + '1')
channel.subscribed(() => {
}).listen('.chat', (event) => {
    chatStore.newMessages.push(event.message)
})


// add a WatchEffect here to update the time stamps
// every few minutes.

function time(e) {
    let formattedTime = dayjs().to(dayjs(e));
    return formattedTime;
}

// scrollToMe button:
function scrollTo(selector) {
    document.querySelector(selector).scrollIntoView({ behavior: 'smooth'});
}

onUpdated(() => {
    scrollTo('#scrollToMe')
    // if (chatStore.newMessages[0]) {
    //     document.getElementById(chatStore.newMessages[0].id).scrollIntoView({behavior: "smooth"})
    // }
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
        this.$nextTick(() => this.scrollToEnd());

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
