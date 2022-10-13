<template>
    <Head title="Schedule" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col md:pageWidth pageWidthSmall h-screen px-10">
        <div class="flex justify-between p-5 mb-5">
            <div class="text-3xl font-semibold">Conversation</div>
        </div>

            <div>
            <chat-messages :messages="messages"></chat-messages>
            </div>
            <div>
            <input-message v-on:messagesent="getMessages"></input-message>
            </div>
    </div>
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import {onMounted, ref, watch} from "vue";
import InputMessage from "@/Components/Chat/InputMessage"
import ChatMessages from "@/Components/Chat/MessagesContainer"
import throttle from "lodash/throttle";
import {Inertia} from "@inertiajs/inertia";

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

let props = defineProps({

})

let messages = ref([])
let currentRoomId = 1

onMounted(() => {
    videoPlayer.makeVideoTopRight();
    getMessages();
});

// let messages = ref(props.messages);
//
// watch(messages, throttle(function (value) {
//     Inertia.get('/messages', { messages: value }, {
//         preserveState: true,
//         replace: true
//     });
// }, 300));

// let search = ref(props.filters.search);
//
// watch(search, throttle(function (value) {
//     Inertia.get('/teams', { search: value }, {
//         preserveState: true,
//         replace: true
//     });
// }, 300));

// const messages = ref()
// const getMessages = toRef(props, 'messages')
// watch(getMessages, (value) => {
//     props.messages.value = getMessages.value
// })



function connect() {
    if( currentRoomId == 1) {
        // let vm = getMessages();
        window.Echo.private("chat." + currentRoomId)
            .listen('.message.new', e => {
                getMessages();
            })
    }
}
function getMessages() {
    //GET request to the messages route in our Laravel server to fetch all the messages
    axios.get('/messages').then(response => {
        //Save the response in the messages array to display on the chat view
        messages.value = response.data;
    })
        .catch( error => {
            console.log(error);
        })
}

watch: {connect()}


</script>


