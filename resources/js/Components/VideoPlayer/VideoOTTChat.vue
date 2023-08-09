<template>
    <div class="scrollbar-hide">
        <div class="scrollbar-hide">
            <VideoOTTChatMessages />
        </div>
        <div>
            <VideoOTTChatInput :channel="currentChannel" v-on:messageSent="getMessages" :user="props.user" />
        </div>
    </div>
</template>

<script setup>
import VideoOTTChatInput from "@/Components/VideoPlayer/VideoOTTChatInput.vue";
import VideoOTTChatMessages from "@/Components/VideoPlayer/VideoOTTChatMessages.vue";
import { ref, onMounted, onBeforeUnmount, onBeforeMount, watchEffect } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";
import { useChatStore } from "@/Stores/ChatStore";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

let videoPlayerStore = useVideoPlayerStore()
let chatStore = useChatStore()

let props = defineProps({
    user: Object,
    currentChannel: ref([]),
})

let channels = ref([])
let messages = ref([])
let newMessage = ref([])

// let pusher = new Pusher('da43e0860b26276ba7c2', {
//     cluster: 'us3'
// });

// let channel = pusher.subscribe('private-chat.' + chatStore.currentChannel.id);
// channel.bind('my-event', function(data) {
//     props.message.push(JSON.stringify(data));
// });

onBeforeMount(async() => {
    console.log('Load VideoOTTChat.vue');
    await connect();
});

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

    // const options = {
    //     broadcaster: 'pusher',
    //     key: 'da43e0860b26276ba7c2'
    // }
    let responseData = null
    let messages = null
    let users = null
    // authorize connection
    // window.Echo = new Echo({
    //     // ...
    //     authorizer: (channel, options) => {
    //         return {
    //             authorize: (socketId, callback) => {
    //                 axios.post('/broadcasting/auth', {
    //                     socket_id: socketId,
    //                     channel_name: "private-chat." + chatStore.currentChannel.id
    //                 })
    //                     .then(response => {
    //                         callback(null, response.data);
    //                         responseData = response.data;
    //                     })
    //                     // .then(pusher.subscribe('private-chat.' + chatStore.currentChannel.id))
    //                     .catch(error => {
    //                         callback(error);
    //                     });
    //             }
    //         };
    //     },
    //     client: new Pusher(options.key, {channelAuthorization:responseData}),
    // })

    // window.Echo.join('private-chat.' + chatStore.currentChannel.id)
    //     .here(users => {
    //         this.users = users;
    //     })
    //     .joining(user => {
    //         this.users.push(chatStore.user.id);
    //         console.log(chatStore.user.name + 'joined chat.')
    //     })
    //     .leaving(user => {
    //         this.users = this.users.filter(u => u.id !== chatStore.user.id);
    //     })
    //     .listen('message.new', (event) => {
    //         this.messages.push({
    //             message: event.message.message,
    //             user: event.user
    //         });
    //
    //         this.users.forEach((user, index) => {
    //             if (user.id === event.user.id) {
    //                 user.typing = false;
    //                 this.$set(this.users, index, user);
    //             }
    //         });
    //     });

    window.Echo.channel('private-chat.' + chatStore.currentChannel.id)
        .listen('newMessage', (e) => {
            console.log('new Message !!!!')
            getMessages()
            // if(e.user.id !== props.user.id) {
            //     this.messages.push({
            //         chatMessage: chatStore.messages
            //     });
            // }
        });

    // window.Echo.private('private-chat.' + chatStore.currentChannel.id)
    //     .listen('.message.new', e => {
    //         console.log('PINIA NEW MESSAGE.');
    //         console.log(e.chatMessage);
    //         // chatStore.messages.push(e.chatMessage);
    //         axios.get('/chat/channel/' + chatStore.currentChannel.id + '/messages')
    //             .then( response => {
    //                 chatStore.messages = response.data;
    //             })
    //             .catch(error => {
    //                 console.log(error);
    //             })
    //     });
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
    console.log('LOAD MESSAGES');
}

// tec21: all good. 11/05
function disconnect() {
    window.Echo.leave("private-chat." + chatStore.currentChannel.id );

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
