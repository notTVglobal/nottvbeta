<template>
  <div class="z-60">

    <form @submit.prevent="">
      <div class="flex">
        <input
            class="p-2 w-fit text-black form-control border-2 border-gray-800 hover:border-blue-800 focus:outline-none"
            type="text"
            ref="messageInput"
            maxlength=”300″
            placeholder="Write a message..."
            v-model="form.message"
            @keyup.enter="sendMessage"
            v-on:focus="chatStore.turnPipChatModeOn"
            v-on:blur="chatStore.turnPipChatModeOff"
        />
        <div v-if="!userStore.isMobile" @click="sendMessage"
             class="ml-2 mt-2 w-fit text-white form-control cursor-pointer">
          <font-awesome-icon icon="fa-paper-plane" class="hover:text-blue-800 text-xl"/>
        </div>
      </div>
      <div class="ml-2 mt-1 font-thin text-xs">{{ form.message.length }}</div>

    </form>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useForm } from "@inertiajs/inertia-vue3"
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from "@/Stores/UserStore"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useChatStore } from "@/Stores/ChatStore"

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()
const chatStore = useChatStore()

let props = defineProps({
  user: Object,
});

const messageInput = ref(null); // Ref for the input element

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
  mounted: (el) => el.focus()
}

// const focusInput = () => {
//     if (userStore.isMobile) {
//         videoPlayerStore.makeVideoPiP()
//         appSettingStore.ott = false
//         videoPlayerStore.ottButtons = false
//     }
// }
//
// let blurInput = () => {
//     if (userStore.isMobile) {
//         videoPlayerStore.makeVideoFullPage();
//         appSettingStore.togglePipChatMode();
//         console.log('toggle PiP Chat Mode: blur Input')
//     }
// };

function sendMessage() {
  //
  if (form.message === "") {
    return;
  }
  if (form.message.length > 300) {
    alert('Message must be 300 characters or shorter.');
    return;
  }
  //POST request to the messages route with the message data in order for our Laravel server to broadcast it.
  axios.post('/chat/message', {
    message: form.message,
    channel_id: chatStore.currentChannel.id,
    user_name: form.user_name,
    user_profile_photo_path: form.user_profile_photo_path,
  }).then(response => {
    if (response.status === 201) {
      form.message = '';
      chatStore.inputTooLong = false;
      console.log('MESSAGE SENT');

      // Refocus the input element
      if (messageInput.value && userStore.isMobile) {
        messageInput.value.focus();
      }
    }
  })
      .catch(error => {
        console.log(error);
      })

}

</script>
