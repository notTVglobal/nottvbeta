<template>
    <Transition
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        enter-active-class="transition duration-3000"
        leave-active-class="transition duration-2000"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >


        <div v-if="chatStore.showChat" class="chatForStreamPage w-100 bottom-0 sm:bottom-8 bg-opacity-30 bg-gray-600 text-sm text-white pb-12
            chat-mask overflow-y-auto scroll-smooth hover:scroll-auto break-words">


            <div class="flex flex-col p-5 mb-5">
                <div class="text-3xl font-semibold">Conversation</div>
                <div class="text-xl">Please scroll to the bottom. We are in the process of building an auto-scroll function.</div>
            </div>

            <div>
                <chat-messages :messages="messages"></chat-messages>
            </div>
            <div>
                <input-message v-on:messagesent="getMessages"></input-message>
            </div>


<!--            <form @submit.prevent="submit">-->
<!--                    <input-->
<!--                        class="fixed bottom-0 right-10 p-2 m-2 mb-10 w-fit text-black form-control border-2 border-gray-800 hover:border-indigo-300 focus:outline-none"-->
<!--                        placeholder="Write a message..." v-model="message"/>-->
<!--                <div @click="submit" class="fixed bottom-0 right-0 p-2 m-2 mb-10 w-fit text-black form-control cursor-pointer">-->
<!--                    <font-awesome-icon icon="fa-paper-plane" class="hover:text-indigo-300 text-xl"/>-->
<!--                </div>-->
<!--            </form>-->

            <button @click="chatStore.showChat = false"
                    v-if="chatStore.showChat" class="opacity-80 w-15 h-15 p-4 rounded-full bg-orange-800
                    text-gray-50 hover:bg-blue-800 hover:text-blue-200 grid justify-center content-center
                    right-36 cursor-pointer font-semibold text-xs">
                CLOSE CHAT
            </button>
        </div>

    </Transition>
</template>

<script setup>
import {useChatStore} from "@/Stores/ChatStore";
import {ref, onMounted, watch} from "vue"
import InputMessage from "@/Components/Chat/InputMessage"
import ChatMessages from "@/Components/Chat/MessagesContainer"

let chatStore = useChatStore();

let props = defineProps({
    user: Object,
});

let messages = ref([])
let currentRoomId = 1

onMounted(() => {
    getMessages();
});

function connect() {
    if( currentRoomId == 1) {
        let vm = getMessages();
        window.Echo.private("chat." + currentRoomId)
            .listen('.message.new', e => {
                vm.getMessages();
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

watch(messages, getMessages)

</script>
