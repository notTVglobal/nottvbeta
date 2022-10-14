<template>
    <div class="flex flex-col p-5 mt-10 mb-5">
        <div class="text-3xl font-semibold">Conversation</div>
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
import {ref, onMounted, watch, onBeforeUnmount } from "vue";
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore";

let videoPlayer = useVideoPlayerStore()

let props = defineProps({
    channels: Object,
    currentChannel: ref([]),
    messages: Object,
    message: Object,
})

let channels = ref([])
let currentChannel = ref([])
let messages = ref([])
let newMessage = ref('')


onMounted(() => {
    getChannels();
    connect();
    // watch(messages, listenForMessages)
});

watch(messages, listenForMessages)

function connect() {
    if( currentChannel.id ) {
        window.Echo.private("chat." + currentChannel.id)
            .listen('.message.new', e => {
                getMessages();
                console.log('CHAT CONNECTED');
            })
    }
}

function getChannels() {
    axios.get('/chat/channels')
        .then(response => {
            channels = response;
            setChannel(channels.data[0]);
            console.log('CURRENT CHANNEL: ' + currentChannel.id);
        })
        .catch(error => {
            console.log(error);
        })
}

function setChannel ( channel ){
    currentChannel = channel;
    videoPlayer.currentChannel = channel;
    getMessages();
}

function getMessages() {
    axios.get('/chat/channel/' + videoPlayer.currentChannel.id + '/messages')
        .then( response => {
            messages.value = response.data;
        })
        .catch(error => {
            console.log(error);
        })

}

function disconnect() {
    window.Echo.leave("chat." + currentChannel.id );
}

 function listenForMessages() {
     window.Echo.private("chat." + currentChannel.id)
         .listen('.message.new', (e) => {
             getMessages();
             console.log('NEW MESSAGE');
         });
 }



onBeforeUnmount(() => {
    disconnect();
});

</script>
