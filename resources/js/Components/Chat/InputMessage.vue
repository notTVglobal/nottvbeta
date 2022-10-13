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

            <input
                class="fixed bottom-0 right-10 p-2 m-2 mb-10 w-fit text-black form-control border-2 border-gray-800 hover:border-blue-800 focus:outline-none"
                type="text"
                placeholder="Write a message..."
                v-model="form.message"
                @keyup.enter="sendMessage"
            />

            <span class="input-group-btn px-3">
                <div @click="sendMessage" class="fixed bottom-0 right-0 p-2 m-2 mb-10 w-fit text-black form-control cursor-pointer">
                    <font-awesome-icon icon="fa-paper-plane" class="hover:text-blue-800 text-xl"/>
                </div>
<!--              <button class="fixed bottom-0 right-0 p-2 m-2 mb-10 w-fit text-black form-control cursor-pointer"-->
<!--                   id="btn-chat" @click="sendMessage">-->
<!--                Send-->
<!--              </button>-->
            </span>
    </form>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/inertia-vue3"

let form = useForm({
    message: '',
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
    axios.post('/messages', {message: form.message}).then(response => {
        if( response.status == 201 ) {
            form.message = '';
            emit('messagesent');
        }
    })
        .catch( error => {
            console.log( error );
        })


}


// function sendMessage() {
//     emit('messagesent', ['user','message'])
//     //Emit a "messagesent" event including the user who sent the message along with the message content
//     // this.$emit("messagesent", {
//     //     user: user,
//     //     //newMessage is bound to the earlier "btn-input" input field
//     //     message: newMessage,
//     // });
//     //Clear the input
//
// }
</script>
