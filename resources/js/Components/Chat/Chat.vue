<template>
    <div class="flex justify-between p-5 mb-5">
        <div class="text-3xl font-semibold">Conversation</div>
    </div>

    <div>
        <messages-container :messages="messages"></messages-container>
    </div>
    <div>
<!--        <input-message v-on:messagesent="getMessages"></input-message>-->
        <input-message></input-message>
    </div>

</template>


<script setup>
import {onMounted, ref, watchEffect, watch} from "vue";
import InputMessage from "@/Components/Chat/InputMessage"
import MessagesContainer from "@/Components/Chat/MessagesContainer"

let messages = ref([])
let currentRoomId = 1

onMounted(() => {
    getMessages();
});

function connect() {
    if( currentRoomId === 1) {
        // let vm = getMessages();
        window.Echo.private("chat." + currentRoomId)
            .listen('message.new', e => {
                // vm.getMessages();
                // getMessages();
                console.log('LISTEN FOR MESSAGE - MESSAGE RECEIVED')
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
// tec21: watch is working to get messages when a message is sent...
// use this if Echo isn't working.
// watch(messages, getMessages)
// watchEffect(() => connect(messages));

</script>
