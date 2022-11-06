<template>
    <div class="input-group">

        <form @submit.prevent="">

<!--            <input-->
<!--                id="btn-input"-->
<!--                type="text"-->
<!--                name="message"-->
<!--                class="form-control input-sm text-gray-800"-->
<!--                placeholder="Type your message here..."-->
<!--                v-model="form.message"-->
<!--            />-->


<!--            Install emojis  -->
<!--            https://github.com/delowardev/vue3-emoji-picker   -->


<!-- If the current page view is NOT the /chat page -->
            <input
                v-if="videoPlayerStore.currentView != 'chat'"
                class="fixed bottom-0 right-10 p-2 m-2 mb-10 w-fit text-black form-control border-2 border-gray-800 hover:border-blue-800 focus:outline-none"
                type="text"
                placeholder="Write a message..."
                v-model="form.message"
                @keyup.enter="sendMessage"
                @blur="sendMessage"
            />
            <span
                v-if="videoPlayerStore.currentView != 'chat'"
                class="input-group-btn px-3">
                <div @click="sendMessage" class="fixed bottom-0 right-0 p-2 m-2 mb-10 w-fit text-white form-control cursor-pointer">
                    <font-awesome-icon icon="fa-paper-plane" class="hover:text-blue-800 text-xl"/>
                </div>
            </span>


<!-- If the current page view is the /chat page -->
            <div
                v-if="videoPlayerStore.currentView === 'chat'"
                class="fixed right-96 pl-28 mr-28 bottom-0 flex flex-row mb-10 w-fit"
            >
                <input
                    v-if="videoPlayerStore.currentView === 'chat'"
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
<!--            <EmojiPicker-->
<!--                picker-type="input"-->
<!--                @select="onSelectEmoji"-->
<!--                class="p-2 m-2 text-black form-control border-2 border-gray-800 hover:border-blue-800 focus:outline-none"/>-->



    </form>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/inertia-vue3"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import EmojiPicker from 'vue3-emoji-picker'
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

// event callback
// function onSelectEmoji(emoji) {
//     // emoji: document.getElementById('input').innerHTML;
//     props.input.value += emoji.i;
//     console.log(emoji)
//     /*
//       // result
//       {
//           i: "😚",
//           n: ["kissing face"],
//           r: "1f61a", // with skin tone
//           t: "neutral", // skin tone
//           u: "1f61a" // without tone
//       }
//       */
// }

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
