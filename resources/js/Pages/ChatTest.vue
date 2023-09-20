<template>
<!--    <Head title="Chat Test" />-->
    <div class="mt-10 ml-5">
<!--        <div class="box">-->
<!--            <p v-if="!messages.length">Start typing the first message</p>-->

<!--            <div v-for="message in messages">-->
<!--                <chat-test-my-message-->
<!--                    v-if="message.user === userId"-->
<!--                    :message="message.text"-->
<!--                ></chat-test-my-message>-->

<!--                <chat-test-message-->
<!--                    v-if="message.user !== userId"-->
<!--                    :message="message.text"-->
<!--                    :user="message.user"-->
<!--                ></chat-test-message>-->
<!--            </div>-->
<!--        </div>-->

<!--        <form @submit.prevent="sendMessage">-->
<!--            <div class="field has-addons has-addons-fullwidth">-->
<!--                <div class="control is-expanded">-->
<!--                    <input class="input" type="text" placeholder="Type a message" v-model="newMessage">-->
<!--                </div>-->
<!--                <div class="control">-->
<!--                    <button type="submit" class="button is-danger" :disabled="!newMessage">-->
<!--                        Send-->
<!--                    </button>-->
<!--                </div>-->
<!--            </div>-->
<!--        </form>-->

        <div id="messages" v-for="(message, index) in chat.messages" :key="index">
            <div>{{message}}</div>
<!--            <message-item :id="message.id" :message="message" :time="time(message.created_at)"/>-->
        </div>

        <form @submit.prevent="">
            <label for="input-message"></label>
            <input type="text"
                   placeholder="Type a message"
                   v-model="form.message"
                   class="text-black"
                   @keyup.enter="sendMessage">
                            <div class="control">
                                <button type="submit" class="button is-danger" :disabled="!newMessage" @click="sendMessage" >
                                    Send
                                </button>
                            </div>
        </form>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
import { useChatStore } from "@/Stores/ChatStore.js";
import {onMounted} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";

let videoPlayerStore = useVideoPlayerStore()
let chat = useChatStore()

let form = useForm({
    message: '',
});

userStore.currentPage = 'chatTest'

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
});

const channel = Echo.private('private.chatTest.1')
channel.subscribed(() => {
    console.log('Joined Chat Channel: ' + 'playground')
}).listen('.playground', (event) => {
    console.log(event)
    chat.messages.push(event.message)
})

function sendMessage() {
    //
    if (form.message === ""){
        return;
    }
    //POST request to the messages route with the message data in order for our Laravel server to broadcast it.
    axios.post('/api/chatTest', {
        message: form.message,
        // channel_id: chatStore.currentChannel.id,
        // channel_id: 'playground',
        // user_name: form.user_name,
        // user_profile_photo_path: form.user_profile_photo_path,
    }).then(response => {
        if( response.status === 201 ) {
            form.message = '';
            // emit('messagesent');
            console.log( 'NEW MESSAGE SENT WITH AXIOS.POST' );
        }
    })
        .catch( error => {
            console.log( error );
        })

}

</script>

<!--<script>-->
<!--import ChatTestMyMessage from "../Components/Chat/ChatTestMyMessage.vue";-->
<!--import ChatTestMessage from "../Components/Chat/ChatTestMessage.vue";-->

<!--export default {-->
<!--    components: {ChatTestMessage, ChatTestMyMessage},-->
<!--    data () {-->
<!--        return {-->
<!--            userId: Math.random().toString(36).slice(-5),-->
<!--            messages: [],-->
<!--            newMessage: ''-->
<!--        }-->
<!--    },-->
<!--    mounted () {-->
<!--        // Echo.channel('playground')-->
<!--        //     .listen('NewChatMessage', (e) => {-->
<!--        //         if(e.user !== this.userId) {-->
<!--        //             this.messages.push({-->
<!--        //                 text: e.message,-->
<!--        //                 user: e.user-->
<!--        //             });-->
<!--        //         }-->
<!--        //     });-->
<!--    },-->
<!--    methods: {-->
<!--        submit() {-->
<!--            axios.post(`/api/chatTestMessage`, {-->
<!--                user: this.userId,-->
<!--                message: this.newMessage-->
<!--            }).then((response) => {-->
<!--                this.messages.push({-->
<!--                    text: this.newMessage,-->
<!--                    user: this.userId-->
<!--                });-->

<!--                this.newMessage = '';-->
<!--            }, (error) => {-->
<!--                console.log(error);-->
<!--            });-->

<!--        }-->
<!--    }-->
<!--}-->
<!--</script>-->


