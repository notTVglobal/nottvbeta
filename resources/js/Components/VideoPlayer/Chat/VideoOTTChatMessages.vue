<template>
    <div class="scrollbar-hide">
        <button @click.prevent="scrollTo('#scrollToMe')" class="bottom-0 mr-32 h-12 bg-blue-800 hover:bg-blue-600 w-56 rounded-lg hidden">CLICK HERE TO SCROLL TO BOTTOM</button>
        <div class="videoOttChatMessages chatChrome w-full h-full pt-5 bottom-0 flex flex-col-reverse overflow-y-scroll overflow-x-clip break-words messages scrollbar-hide">
            <div id="scrollToMe"></div>

            <div id="newMessages" v-for="(message, messages) in chatStore.newMessages.slice().reverse()" :key="messages">
                <message-item :id="message.id" :message="message"/>
            </div>
            <div id="oldMessages" v-for="(message, messages) in chatStore.oldMessages.slice()" :key="messages">
                <message-item :id="message.id" :message="message"/>
            </div>

        </div>
    </div>
</template>

<script setup>
import {onBeforeMount, onBeforeUnmount, onMounted, onUpdated, ref} from "vue";

import MessageItem from "@/Components/VideoPlayer/Chat/ChatMessage.vue"
import {useChatStore} from "@/Stores/ChatStore";
import {useUserStore} from "@/Stores/UserStore";
import dayjs from 'dayjs';
import relativeTime from "dayjs/plugin/relativeTime";

let chatStore = useChatStore()
let userStore = useUserStore()

let props = defineProps({
    message: Object,
})

let channels = ref([])

const channel = Echo.private('chat.' + '1')
channel.subscribed(() => {
}).listen('.chat', (event) => {
    chatStore.newMessages.push(event.message)
})


onBeforeMount(async() => {
    await connect();
});

onMounted(() => {

})

onUpdated(() => {
    scrollTo('#scrollToMe')
    // if (chatStore.newMessages[0]) {
    //     document.getElementById(chatStore.newMessages[0].id).scrollIntoView({behavior: "smooth"})
    // }
})

onBeforeUnmount(() => {
    chatStore.newMessages = [];
    disconnect();
});

// dayjs.extend(relativeTime)

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
    console.log('STREAM CHAT DISCONNECTED');
}


// add a WatchEffect here to update the time stamps
// every few minutes.

// scrollToMe button:
function scrollTo(selector) {
    document.querySelector(selector).scrollIntoView({ behavior: 'smooth'});
}



// tec21: this is for setting a timer to update the timestamps on the messages.
// onUnmounted( () => {
//     clearInterval(this.timer)
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
