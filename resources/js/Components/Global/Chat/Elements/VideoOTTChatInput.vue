<template>
  <div>

    <form @submit.prevent="">
      <div class="flex">
        <input
            class="right-auto lg:right-10 p-2 w-fit text-black form-control border-2 border-gray-800 hover:border-blue-800 focus:outline-none"
            type="text"
            maxlength=”300″
            placeholder="Write a message..."
            v-model="form.message"
            @keyup.enter="sendMessage"
            v-on:focus="chatStore.turnPipChatModeOn"
            v-on:blur="chatStore.turnPipChatModeOff"
        />
        <div @click="sendMessage" class="right-auto lg:right-5 p-2 w-fit text-white form-control cursor-pointer">
          <font-awesome-icon icon="fa-paper-plane" class="hover:text-blue-800 text-xl"/>
        </div>
      </div>
      <div>
        <div class="pt-2 right-auto lg:right-10 font-thin text-xs">{{ form.message.length }}</div>
      </div>


    </form>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useForm } from "@inertiajs/inertia-vue3"
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useChatStore } from "@/Stores/ChatStore"

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()
const chatStore = useChatStore()

let props = defineProps({
  user: Object,
});

let form = useForm({
  message: '',
  user_name: props.user.name,
  user_profile_photo_path: props.user.profile_photo_path,
  user_profile_photo_url: props.user.profile_photo_url,
});

// Watch the form.message for changes
watch(() => form.message, (newValue) => {
  chatStore.inputTooLong = newValue.length > 300;
});


const vFocus = {
  mounted: (el) => el.focus(),
}

// const focusInput = () => {
//     if (userStore.isMobile) {
//         videoPlayerStore.makeVideoPiP()
//         console.log('toggle PiP Chat Mode: focus Input')
//     }
// }
//
// let blurInput = () => {
//     if (userStore.isMobile) {
//         videoPlayerStore.makeVideoTopRight();
//         appSettingStore.togglePipChatMode();
//         console.log('toggle PiP Chat Mode: blur Input')
//     }
// };


function sendMessage() {
  //
  if (form.message === "") {
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
    if (response.status === 201) {
      form.message = '';
      console.log('MESSAGE SENT');
    }
  })
      .catch(error => {
        console.log(error);
      })

}

</script>
