<template>
    <div>

        <form @submit.prevent="">

            <input
                class="fixed bottom-0 right-10 p-2 m-2 mb-10 w-fit text-black form-control border-2 border-gray-800 hover:border-blue-800 focus:outline-none"
                type="text"
                placeholder="Write a message..."
                v-model="form.message"
                @keyup.enter="sendMessage"
            />
            <span>
                <div @click="sendMessage" class="fixed bottom-0 right-0 p-2 m-2 mb-10 w-fit text-white form-control cursor-pointer">
                    <font-awesome-icon icon="fa-paper-plane" class="hover:text-blue-800 text-xl"/>
                </div>
            </span>

        </form>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/inertia-vue3"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import {ref} from 'vue'

let videoPlayerStore = useVideoPlayerStore()
let chatStore = useChatStore()

let props = defineProps({
    channel: Object,
    user: Object,
    // search: String,
    input: ref(''),
});


let form = useForm({
    message: '',
    user_name: props.user.name,
    user_profile_photo_path: props.user.profile_photo_path,
    user_profile_photo_url: props.user.profile_photo_url,
});


const emit = defineEmits(['messagesent'])

function sendMessage() {
    //
    if (form.message === ""){
        return;
    }
    //POST request to the messages route with the message data in order for our Laravel server to broadcast it.
    axios.post('/chat/message', {
        message: form.message,
        channel_id: chatStore.currentChannel.id,
        user_name: form.user_name,
        user_profile_photo_path: form.user_profile_photo_path,
    }).then(response => {
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
