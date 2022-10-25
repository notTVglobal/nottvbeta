<template>
    <div class="flex flex-col p-5 mt-10 mb-5">
        <div class="text-3xl font-semibold">Conversation</div> new message: {{ }}
        <div class="text-xl">Please scroll to the bottom. We are in the process of building an auto-scroll function.</div>
    </div>
    <div>
        <chat-messages :messages="chatStore.messages"></chat-messages>
    </div>
    <div>
        <input-message :channel="currentChannel" v-on:messagesent="getMessages" :user="props.user"></input-message>
    </div>
</template>

<script setup>
import InputMessage from "@/Components/Chat/InputMessage"
import ChatMessages from "@/Components/Chat/MessagesContainer"
import {ref, onMounted, watch, onBeforeUnmount, onBeforeMount, watchEffect, effect} from "vue";
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore";
import {useChatStore} from "@/Stores/ChatStore";
import {default as Echo} from "laravel-echo";

let videoPlayer = useVideoPlayerStore()
let chatStore = useChatStore()

let props = defineProps({
    user: Object,
    // channels: Object,
    // currentChannel: ref([]),
    // messages: ref([]),
    // message: ref([]),
    // newMessage: ref([]),
})

let channels = ref([])
let messages = ref([])
let newMessage = ref([])
// let messages = ref(chatStore.messages)

onBeforeMount(async() => {
    await connect();

    // window.Echo.private('chat.1').listen('.message.new', ({ chatMessage }) => {
    //     // messages.value = newMessage.data;
    //     console.log(chatMessage.data);
    // })

    // window.Echo.private("chat." + currentChannel.id)
    //     .listen('.message.new', e => {
    //         getMessages();
    //     });
    // console.log('MESSAGE LOADED');

    // window.Echo.private('chat.' + `${chatStore.currentChannel.id}`)

    window.Echo.private('chat.' + chatStore.currentChannel.id)
        .listen('.message.new', e => {
            console.log('PINIA NEW MESSAGE.');
            console.log(e.chatMessage);
            // chatStore.messages.push(e.chatMessage);
            axios.get('/chat/channel/' + chatStore.currentChannel.id + '/messages')
                .then( response => {
                    chatStore.messages = response.data;
                    // chatStore.messages = response.data;
                })
                .catch(error => {
                    console.log(error);
                })
        });

});

onMounted(async () => {
    // window.Echo.private('chat.1')
    //     .listen('.message.new', e => {
    //         console.log('NEW ECHO ' + e.chatMessage.message)
    //         messages.value = e.data;
    //     });
    await console.log('test1: ' + chatStore.currentChannel.id);



})

// window.Echo.private("chat." + currentChannel.id)
//     .listen('.message.new', e => {
//         messages.value = e;
//         console.log('KEEP LISTENING??');
//     });


// window.Echo.private("chat." + currentChannel.id)
//     .listen('.message.new', e => {
//         messages.push(e);
//     });

// window.Echo.private("chat." + currentChannel.id)
//     .listen('.message.new', (e) => {
//         console.log('NEW MESSAGE RECEIVED');
//         console.log(e.chatMessage);
//         getNewMessage()
//     });

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
    getChannels();
}

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

function setChannel ( channel ){
    chatStore.currentChannel = channel;
    console.log('SET CHANNEL');
    console.log('CURRENT CHANNEL: ' + chatStore.currentChannel.name);
    getMessages();
    console.log('test2: ' + chatStore.currentChannel.id);
}

function getMessages() {
    axios.get('/chat/channel/' + chatStore.currentChannel.id + '/messages')
        .then( response => {
            chatStore.messages = response.data;
            // chatStore.messages = response.data;
        })
        .catch(error => {
            console.log(error);
        })
        console.log('GET MESSAGES');
}

function disconnect() {
    window.Echo.leave("chat." + chatStore.currentChannel.id );
    console.log('STREAM CHAT DISCONNECTED');
}

 // function listenForNewMessage() {
 //     window.Echo.private("chat." + currentChannel.id)
 //         .listen('.message.new', e => {
 //             getMessages();
 //         });
 // }



watchEffect(() => {
    // window.Echo = new Echo({
    //     broadcaster: 'pusher',
    //     key: process.env.MIX_PUSHER_APP_KEY,
    //     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    //     encrypted: true,
    //     forceTLS: true
    // });
    // let videoPlayer = useVideoPlayerStore();
    // window.Echo.private('chat.1')
    //     .listen('.message.new', e => {
    //         console.log('PINIA NEW MESSAGE.');
    //         console.log(e.chatMessage);
    //         // this.messages.value = e.chatMessage;
    //         axios.get('/chat/channel/' + videoPlayer.currentChannel.id + '/messages')
    //             .then( response => {
    //                 chatStore.messages = response.data;
    //                 // chatStore.messages = response.data;
    //             })
    //             .catch(error => {
    //                 console.log(error);
    //             })
    //     });
});


onBeforeUnmount(() => {
    disconnect();
});

</script>
