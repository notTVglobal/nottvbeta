<template>
    <div class="">
        <div class="">
            <VideoOTTChatMessages />
        </div>
        <div>
            <VideoOTTChatInput :channel="currentChannel" v-on:messagesent="getMessages" :user="props.user" />
        </div>
    </div>
</template>

<script setup>
import VideoOTTChatInput from "@/Components/VideoPlayer/VideoOTTChatInput.vue";
import VideoOTTChatMessages from "@/Components/VideoPlayer/VideoOTTChatMessages.vue";
import { ref, onMounted, onBeforeUnmount, onBeforeMount, watchEffect } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";
import { useChatStore } from "@/Stores/ChatStore";

let videoPlayer = useVideoPlayerStore()
let chatStore = useChatStore()

let props = defineProps({
    user: Object,
    currentChannel: ref([]),
})

let channels = ref([])
let messages = ref([])
let newMessage = ref([])

onBeforeMount(async() => {
    await connect();
});

onMounted(async () => {
    // tec21: this is undefined. 11/05
    await console.log('test1: ' + chatStore.currentChannel.id);
})

// tec21: all good. 11/05
function connect() {
    console.log('STREAM CHAT CONNECTED');
    getChannels();
}

// tec21: all good. 11/05
function getChannels() {
    console.log('GET CHANNELS');
    axios.get('/chat/channels')
        .then(response => {
            channels = response;
            setChannel(channels.data[0]);
        })
        .catch(error => {
            console.log(error);
        })
}

// tec21: all good. 11/05
function setChannel ( channel ){
    chatStore.currentChannel = channel;
    console.log('SET CHANNEL');
    console.log('CURRENT CHANNEL: ' + chatStore.currentChannel.name);
    getMessages();

    // tec21: all good. 11/05
    console.log('test2: ' + chatStore.currentChannel.id);

    window.Echo.private('chat.' + chatStore.currentChannel.id)
        .listen('.message.new', e => {
            console.log('PINIA NEW MESSAGE.');
            console.log(e.chatMessage);
            // chatStore.messages.push(e.chatMessage);
            axios.get('/chat/channel/' + chatStore.currentChannel.id + '/messages')
                .then( response => {
                    chatStore.messages = response.data;
                })
                .catch(error => {
                    console.log(error);
                })
        });
}

// tec21: all good. 11/05
function getMessages() {
    axios.get('/chat/channel/' + chatStore.currentChannel.id + '/messages')
        .then( response => {
            chatStore.messages = response.data;
            // chatStore.messages = response.data;
        })
        .catch(error => {
            console.log(error);
        })

    // tec21: all good. 11/05
    console.log('GET MESSAGES');
}

// tec21: all good. 11/05
function disconnect() {
    window.Echo.leave("chat." + chatStore.currentChannel.id );

    // tec21: all good. 11/05
    console.log('STREAM CHAT DISCONNECTED');
}

watchEffect(() => {

});

// tec21: all good. 11/05
onBeforeUnmount(() => {
    disconnect();
});

</script>
