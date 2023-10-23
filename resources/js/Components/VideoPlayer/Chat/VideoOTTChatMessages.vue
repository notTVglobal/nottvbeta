<template>
    <div class="scrollbar-hide">
        <div class="videoOttChatMessages chatChrome w-full h-full pt-5 bottom-0 flex flex-col-reverse overflow-y-scroll overflow-x-clip break-words messages scrollbar-hide">
            <div id="scrollToMe"></div>

            <div id="newMessages" v-for="(newMessage, index) in chatStore.newMessages.slice().reverse()" :key="index">
                <message-item :id="newMessage.id" :message="newMessage"/>
            </div>
            <div id="oldMessages" v-for="(oldMessage, index) in chatStore.oldMessages.slice()" :key="index">
                <message-item :id="oldMessage.id" :message="oldMessage"/>
            </div>

        </div>
    </div>
</template>

<script setup>
import {nextTick, onBeforeMount, onBeforeUnmount, onMounted, onUpdated, ref} from "vue";

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
//
// onUpdated(() => {
//     scrollTo('#scrollToMe')
//     // if (chatStore.newMessages[0]) {
//     //     document.getElementById(chatStore.newMessages[0].id).scrollIntoView({behavior: "smooth"})
//     // }
// })

nextTick(() => {
    scrollTo('#scrollToMe')
})

onBeforeUnmount(() => {
    chatStore.newMessages = [];
    chatStore.oldMessages = [];
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
