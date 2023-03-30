<template>
    <div>
        <div class="flex flex-col py-5 mt-10">
            <div class="text-3xl font-semibold">Conversation</div>
        </div>
        <div class="italic">The newest message is at the bottom.</div>
        <div class="absolute">
            <div class="relative h-[calc(h-100%-16rem)] pb-24 top-0">
                <chat-messages />
            </div>
            <div class="relative h-16">
                <input-message :channel="currentChannel" v-on:messagesent="getMessages" :user="props.user" />
            </div>
        </div>
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
    currentChannel: ref([]),
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



});

onMounted(async () => {
    // window.Echo.private('chat.1')
    //     .listen('.message.new', e => {
    //         console.log('NEW ECHO ' + e.chatMessage.message)
    //         messages.value = e.data;
    //     });

    // tec21: this is undefined. 11/05
    await console.log('test1: ' + chatStore.currentChannel.id);



})

// window.Echo.private("chat." + currentChannel.id)
//     .listen('.message.new', e </div>=> {
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
            // setChannel(channels.data[0]);
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

// tec21: all good. 11/05
onBeforeUnmount(() => {
    disconnect();
});

</script>
