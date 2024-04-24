<template>
  <div>

    <form @submit.prevent="">

      <div class="flex">
        <!-- Display error message -->
        <input
            class="right-auto lg:right-10 p-2 w-fit text-black form-control border-2 border-gray-800 hover:border-blue-800 focus:outline-none"
            ref="messageInput"
            type="text"
            maxlength=”300″
            placeholder="Write a message..."
            v-model="form.message"
            @keyup.enter="sendMessage"
            v-on:focus="appSettingStore.turnPipChatModeOn"
            v-on:blur="appSettingStore.turnPipChatModeOff"
        />
        <div @mousedown.prevent="sendMessage"
             class="right-auto lg:right-5 p-2 w-fit text-white form-control cursor-pointer">
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
import { useForm } from '@inertiajs/inertia-vue3'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useChatStore } from '@/Stores/ChatStore'
import { useNotificationStore } from '@/Stores/NotificationStore'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()
const chatStore = useChatStore()
const notificationStore = useNotificationStore()

const messageInput = ref(null)
const errorMessage = ref('')
// const cooldown = ref(false)

let props = defineProps({
  user: Object,
})

let form = useForm({
  message: '',
  user_name: props.user.name,
  user_profile_photo_path: props.user.profile_photo_path,
  user_profile_photo_url: props.user.profile_photo_url,
})

// Watch the form.message for changes
watch(() => form.message, (newValue) => {
  chatStore.inputTooLong = newValue.length > 300
})


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
  if (chatStore.cooldown) {
    chatStore.setErrorMessage('')
    chatStore.setErrorMessage('Whoa there, speedy! Let\'s give others a chance to chime in. Try sending your message in a bit. 🕒')
    return
  }

  if (form.message === '') {
    chatStore.setErrorMessage('Looks like you\'re sending an invisible message! 🕵️‍♂️ Please type something to share with the community.')
    return
  }

  if (form.message.length > 300) {
    chatStore.setErrorMessage('')
    notificationStore.setGeneralServiceNotification('Keep it short and sweet!', 'Messages must be 300 characters or shorter. 😊')
    return
  }

  chatStore.setErrorMessage('')



    //
    //
    // axios.post('/chat/message', {
    //   message: form.message,
    //   channel_id: chatStore.currentChannel.id,
    //   user_name: form.user_name,
    //   user_profile_photo_path: form.user_profile_photo_path,
    //   // Add other necessary fields
    // }).then(response => {
    //   if (response.status === 201) {
    //     form.message = ''
    //     console.log('Message sent – high five!')
    //     errorMessage.value = '' // Clear any previous error message
    //   }
    // }).catch(error => {
    //   if (error.response && error.response.status === 429) {
    //     let countdownSeconds = 60
    //
    //     errorMessage.value = `Hold your horses! Wait for ${countdownSeconds} more seconds before your next message. 🕒`
    //     chatStore.handleCooldown() // Start the cooldown in the store
    //
    //     const cooldownInterval = setInterval(() => {
    //       countdownSeconds -= 1
    //       errorMessage.value = `Hold your horses! Wait for ${countdownSeconds} more seconds before your next message. 🕒`
    //
    //       if (countdownSeconds <= 0) {
    //         clearInterval(cooldownInterval)
    //         errorMessage.value = ''
    //         chatStore.cooldown = false // Reset the cooldown state in the store
    //       }
    //     }, 1000)
    //
    //     // Clear the interval when the component is unmounted
    //     onUnmounted(() => {
    //       clearInterval(cooldownInterval)
    //     })
    //   } else {
    //     console.error(error)
    //     errorMessage.value = 'Oops! Something went wonky on our end. Give it another whirl? 🌀'
    //   }
    // })


























  axios.post('/chat/message', {
    message: form.message,
    channel_id: chatStore.currentChannel.id,
    user_name: form.user_name,
    user_profile_photo_path: form.user_profile_photo_path,
    // Add other necessary fields
  }).then(response => {
    if (response.status === 201) {
      form.message = ''
      console.log('Message sent – high five!')
      errorMessage.value = '' // Clear any previous error message
    }
  }).catch(error => {
    if (error.response && error.response.status === 429) {
      // Start the countdown at 15 seconds
      let countdownSeconds = 60

      // Update the initial cooldown message to include the countdown
      errorMessage.value = `Whoa there, speedy! Let's give others a chance to chime in. Try sending your message in ${countdownSeconds} seconds. 🕒`
      chatStore.cooldown = true

      // Set up a countdown interval
      const cooldownInterval = setInterval(() => {
        countdownSeconds -= 1 // Decrement the countdown
        // Update the message with the new countdown value
        errorMessage.value = `Hold your horses! Wait for ${countdownSeconds} more seconds before your next message. 🕒`

        if (countdownSeconds <= 0) {
          clearInterval(cooldownInterval) // Stop the countdown when it reaches 0
          errorMessage.value = '' // Clear the error message
          chatStore.cooldown = false // Reset the cooldown state
        }
      }, 1000) // Update the countdown every second
    } else {
      // Handle other errors
      console.error(error)
      errorMessage.value = 'Oops! Something went wonky on our end. Give it another whirl? 🌀'
    }
  })

}


// function sendMessage() {
//   messageInput.value.focus();
//   //
//   if (form.message === "") {
//     return;
//   }
//   //POST request to the messages route with the message data in order for our Laravel server to broadcast it.
//
//   if (form.message.length > 300) {
//     alert('Message must be 300 characters or shorter.');
//     return;
//   }
//   axios.post('/chat/message', {
//     message: form.message,
//     channel_id: chatStore.currentChannel.id,
//     user_name: form.user_name,
//     user_profile_photo_path: form.user_profile_photo_path,
//   }).then(response => {
//     if (response.status === 201) {
//       form.message = '';
//       console.log('MESSAGE SENT');
//     }
//   })
//       .catch(error => {
//         console.log(error);
//       })
//
// }

</script>
