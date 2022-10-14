<template>
    <div class="input-group">

        <form @submit.prevent="submit">

<!--            <input-->
<!--                id="btn-input"-->
<!--                type="text"-->
<!--                name="message"-->
<!--                class="form-control input-sm text-gray-800"-->
<!--                placeholder="Type your message here..."-->
<!--                v-model="form.message"-->
<!--            />-->

<!-- If the current page view is NOT the /chat page -->
            <input
                v-if="videoPlayer.currentView != 'chat'"
                class="fixed bottom-0 right-10 p-2 m-2 mb-10 w-fit text-black form-control border-2 border-gray-800 hover:border-blue-800 focus:outline-none"
                type="text"
                placeholder="Write a message..."
                v-model="form.message"
                @keyup.enter="sendMessage"
            />
            <span
                v-if="videoPlayer.currentView != 'chat'"
                class="input-group-btn px-3">
                <div @click="sendMessage" class="fixed bottom-0 right-0 p-2 m-2 mb-10 w-fit text-black form-control cursor-pointer">
                    <font-awesome-icon icon="fa-paper-plane" class="hover:text-blue-800 text-xl"/>
                </div>
            </span>

<!-- If the current page view is the /chat page -->
            <div
                v-if="videoPlayer.currentView === 'chat'"
                class="fixed bottom-0 flex flex-row mb-10 w-fit"
            >
                <input
                    v-if="videoPlayer.currentView === 'chat'"
                    class="p-2 m-2 text-black form-control border-2 border-gray-800 hover:border-blue-800 focus:outline-none"
                    type="text"
                    placeholder="Write a message..."
                    v-model="form.message"
                    @keyup.enter="sendMessage"
                />
                <div @click="sendMessage" class="px-4 pt-4 bg-gray-300 align-content-center hover:bg-blue-600 hover:text-white rounded text-black form-control cursor-pointer">
                    <font-awesome-icon icon="fa-paper-plane" class="text-xl"/>
                </div>
            </div>


    </form>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/inertia-vue3"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"

let videoPlayer = useVideoPlayerStore()

let form = useForm({
    message: '',
})

let props = defineProps({
    channel: Object,
})

const emit = defineEmits(['messagesent'])

function sendMessage() {
    //
    if (form.message === ""){
        return;
    }
    // emit('messagesent', [message])
    //Pushes it to the messages array
    // newMessage.push(getMessages)
    //POST request to the messages route with the message data in order for our Laravel server to broadcast it.
    axios.post('/chat/channel/' + videoPlayer.currentChannel.id + '/message', {message: form.message, channel_id: videoPlayer.currentChannel.id}).then(response => {
        if( response.status == 201 ) {
            form.message = '';
            emit('messagesent');
        }
    })
        .catch( error => {
            console.log( error );
        })


}



</script>
