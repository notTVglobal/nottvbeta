<template>
    <Transition
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        enter-active-class="transition duration-3000"
        leave-active-class="transition duration-2000"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >

        <!--    <div v-if="chatStore.showChat" class="opacity-80 chatForStreamPage w-20 h-20 rounded-full bg-red-400 text-red-100 grid justify-center content-center">-->
        <!--        CHAT GOES HERE-->
        <!--    </div>-->
        <div v-if="chatStore.showChat" class="chatForStreamPage bottom-0 sm:bottom-8 space-x-6 bg-opacity-80 bg-orange-300 text-sm text-white pb-2 pb-2
            chat-mask overflow-y-auto scroll-smooth hover:scroll-auto break-words">


            <div
                class="list-group list-group-flush border-bottom w-full min-h-max max-h-max pt-4 px-2 mb-12 break-words drop-shadow-md shadow-black tracking-wide leading-relaxed subpixel-antialiased">
                <div class="list-group-item pb-2 leading-tight break-words" v-for="message in messages" :key="message.id">
                    <div class="flex w-100 align-items-center justify-content-between">
                        <strong class="mb-1">{{ message.username }}</strong>
                    </div>
                    <div class="col-10 mb-2 small break-words max-w-max" refs="scrollToMe">{{ message.message }}</div>
                </div>
            </div>

            <form @submit.prevent="submit">
                    <input
                        class="fixed bottom-0 right-10 p-2 m-2 mb-10 w-fit text-black form-control border-2 border-gray-800 hover:border-indigo-300 focus:outline-none"
                        placeholder="Write a message..." v-model="message"/>
                <div @click="submit" class="fixed bottom-0 right-0 p-2 m-2 mb-10 w-fit text-black form-control cursor-pointer">
                    <font-awesome-icon icon="fa-paper-plane" class="hover:text-indigo-300 text-xl"/>
                </div>
            </form>

            <button @click="chatStore.showChat = false"
                    v-if="chatStore.showChat" class="opacity-80 w-15 h-15 p-4 rounded-full bg-gray-600
                    text-gray-50 hover:bg-gray-800 hover:text-gray-200 grid justify-center content-center
                    right-36 cursor-pointer font-semibold text-xs">
                CLOSE CHAT
            </button>
        </div>

    </Transition>
</template>

<script setup>
import {useChatStore} from "@/Stores/ChatStore";
import {ref, onMounted} from "vue"
import Pusher from "pusher-js"

let chatStore = useChatStore();

let props = defineProps({
    user: Object,
});

// set username manually in ChatForm
// const username = ref([])
const messages = ref([])
const message = ref('')


onMounted(() => {
    // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = true;

    // Pusher Key for development: 679608fe1b2e6a2bf76b
    // Pusher Key for staging: d03ec1b33bc0f17392c4
    // Pusher Key for production: f0b385d3a5994dca4741
    //
    const pusher = new Pusher('679608fe1b2e6a2bf76b', {
    //     const pusher = new Pusher('d03ec1b33bc0f17392c4', {
        cluster: 'us3'
    });

    const channel = pusher.subscribe('chat');
    channel.bind('message', data => {
        messages.value.push(JSON.stringify(data));

    });
})


// URI for development: http://beta.local:8080/api/messages
// URI for staging: https://beta-staging.not.tv/api/messages
// URI for production: https://beta.not.tv/api/messages
const submit = async () => {
    // await fetch('https://beta-staging.not.tv/api/messages', {
        await fetch('http://beta.local:8080/api/messages', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            username: props.user.name,
            message: message.value
        })
    })

    message.value = '';
    // scrollToElement();
}


</script>
