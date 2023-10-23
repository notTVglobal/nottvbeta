<template>
    <div>

        <form @submit.prevent="">
            <input
                class="fixed right-20 p-2 m-2 mb-2 w-fit text-black form-control border-2 border-gray-800 hover:border-blue-800 focus:outline-none"
                type="text"
                maxlength=”300″
                placeholder="Write a message..."
                v-model="form.message"
                @keyup.enter="sendMessage"
                v-on:blur="videoPlayerStore.makeVideoTopRight()"
                v-on:focus="videoPlayerStore.makeVideoPiP()"
            />
            <div class="fixed mt-12 pt-2 right-20 p-2 m-2 mb-2 font-thin text-xs">{{form.message.length}}</div>

            <div @click="sendMessage" class="fixed right-10 p-2 m-2 mb-2 w-fit text-white form-control cursor-pointer">
                <font-awesome-icon icon="fa-paper-plane" class="hover:text-blue-800 text-xl"/>
            </div>

        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from "@inertiajs/inertia-vue3"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"

let videoPlayerStore = useVideoPlayerStore()
let chatStore = useChatStore()

let props = defineProps({
    user: Object,
    input: ref(''),
});git 

let form = useForm({
    message: '',
    user_name: props.user.name,
    user_profile_photo_path: props.user.profile_photo_path,
    user_profile_photo_url: props.user.profile_photo_url,
});

const vFocus = {
    mounted: (el) => el.focus(),
}

function sendMessage() {
    //
    if (form.message === ""){
        return;
    }
    //POST request to the messages route with the message data in order for our Laravel server to broadcast it.

    if (form.message.length > 300) {
        alert('Message must be 300 characters or shorter.');
        return;
    }
    axios.post('/chat/message', {
        message: form.message,
        channel_id: chatStore.currentChannel.id,
        user_name: form.user_name,
        user_profile_photo_path: form.user_profile_photo_path,
    }).then(response => {
        if( response.status === 201 ) {
            form.message = '';
            console.log( 'MESSAGE SENT' );
        }
    })
        .catch( error => {
            console.log( error );
        })

}

</script>
