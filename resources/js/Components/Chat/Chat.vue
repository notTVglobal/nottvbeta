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
// import Pusher from "pusher-js";

let messages = ref([])
let currentRoomId = 1

// let pusher = Pusher

onMounted(() => {
    getMessages();
});

function connect() {
    if( currentRoomId === 1) {
        // authorize connection
        // window.Echo = new Echo({
        //     // ...
        //     authorizer: (channel, options) => {
        //         return {
        //             authorize: (socketId, callback) => {
        //                 axios.post('/broadcasting/auth', {
        //                     socket_id: socketId,
        //                     channel_name: "private-chat." + currentRoomId
        //                 })
        //                     .then(response => {
        //                         callback(null, response.data);
        //                         pusher.subscribe("private-chat111." + currentRoomId)
        //                     })
        //                     .catch(error => {
        //                         callback(error);
        //                     });
        //             }
        //         };
        //     },
        // })

        // let vm = getMessages();
        window.Echo.private("private-chat." + currentRoomId)
            .listen('message.new', e => {
                // vm.getMessages();
                // getMessages();
                console.log('LISTEN FOR MESSAGE - MESSAGE RECEIVED')
            })

        // pusher.subscribe("private-chat." + currentRoomId)
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
