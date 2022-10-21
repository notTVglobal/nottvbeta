<template>
    <div class="flex flex-col p-5 mt-10 mb-5">
        <div class="text-3xl font-semibold">Conversation</div> new message: {{ }}
        <div class="text-xl">Please scroll to the bottom. We are in the process of building an auto-scroll function.</div>
    </div>
    <div>
        <chat-messages :messages="messages"></chat-messages>
    </div>
    <div>
        <input-message :channel="currentChannel" v-on:messagesent="getMessages"></input-message>
    </div>
</template>

<script setup>
import InputMessage from "@/Components/Chat/InputMessage"
import ChatMessages from "@/Components/Chat/MessagesContainer"
import {ref, onMounted, watch, onBeforeUnmount, onBeforeMount} from "vue";
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore";
import {useChatStore} from "@/Stores/ChatStore";

let videoPlayer = useVideoPlayerStore()
let chatStore = useChatStore()

let props = defineProps({
    channels: Object,
    currentChannel: ref([]),
    messages: ref([]),
    message: ref([]),
    newMessage: ref([]),
})

let channels = ref([])
let currentChannel = ref([])
let messages = ref([])
let newMessage = ref([])

onBeforeMount(() => {
    getChannels();

    // window.Echo.private('chat.1').listen('.message.new', ({ chatMessage }) => {
    //     // messages.value = newMessage.data;
    //     console.log(chatMessage.data);
    // })

    window.Echo.private('chat.1')
        .listen('.message.new', e => {
            console.log('MESSAGE RECEIVED !!.');
            console.log(e.chatMessage);
            getNewMessage()
        });

});

// connect();
// window.Echo.private('chat.' + currentChannel.id)
//     .listen('.message.new', e => {
//         getMessages();
//         console.log('MESSAGE RECEIVED (connect)');
//         console.log(e);
//     })
// function connect() {
//     if( currentChannel.id === 1) {
//         Echo.private("chat." + currentChannel.id)
//             .listen('.message.new', e => {
//                 getMessages();
//                 console.log('STREAM CHAT CONNECTED');
//             })
//     }
// }

function connect() {
    console.log('STREAM CHAT CONNECTED');
}

function getChannels() {
    axios.get('/chat/channels')
        .then(response => {
            channels = response;
            setChannel(channels.data[0]);
            console.log('CURRENT CHANNEL: ' + currentChannel.name);
        })
        .catch(error => {
            console.log(error);
        })
    console.log('GET CHANNELS');
}

function setChannel ( channel ){
    currentChannel = channel;
    videoPlayer.currentChannel = channel;
    console.log('SET CHANNEL');
    getMessages();
    console.log('RETRIEVE MESSAGES');
}

function getMessages() {
    axios.get('/chat/channel/' + videoPlayer.currentChannel.id + '/messages')
        .then( response => {
            messages.value = response.data;
            chatStore.messages = response.data;
        })
        .catch(error => {
            console.log(error);
        })
}

function disconnect() {
    window.Echo.leave("chat." + currentChannel.id );
    console.log('STREAM CHAT DISCONNECTED');
}

 function getNewMessage() {
     window.Echo.private("chat." + currentChannel.id)
         .listen('.message.new', e => {
             getMessages();
         });
     console.log('MESSAGE LOADED');
 }

function watchForNewMessage() {
    window.Echo.private("chat." + currentChannel.id)
        .listen('.message.new', e => {
        });
    console.log('MESSAGE FOUND');
}

watch(messages, getNewMessage);

onBeforeUnmount(() => {
    disconnect();
});

</script>
