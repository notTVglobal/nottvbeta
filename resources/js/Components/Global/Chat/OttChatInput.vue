<template>
  <div>

    <form @submit.prevent="">
      <div class="flex flex-col w-full justify-center items-center">
        <div class="flex flex-col">
          <div class="flex">

            <button type="button" @click.prevent="chatStore.toggleEmojiPicker" class="text-2xl transition-all duration-200 transform hover:scale-125 mr-1">😀</button>
            <input
                class="right-auto  p-2 w-fit text-black form-control border-2 border-gray-800 hover:border-blue-800 focus:outline-none"
                ref="messageInput"
                type="text"
                maxlength=”300″
                placeholder="Write a message..."
                v-model="chatStore.message"
                @keyup.enter="sendMessage"
                v-on:focus="appSettingStore.turnPipChatModeOn"
                v-on:blur="appSettingStore.turnPipChatModeOff"
            />
            <div @mousedown.prevent="sendMessage"
                 class="right-auto lg:right-5 p-2 w-fit text-white form-control cursor-pointer">
              <font-awesome-icon icon="fa-paper-plane" class="hover:text-blue-800 text-xl"/>
            </div>
          </div>
          <div class="flex flex-row w-full justify-start">
            <div class="pt-2 right-auto lg:right-10 font-thin text-xs">{{ chatStore.message.length }}</div>
          </div>
        </div>

      </div>
    </form>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useChatStore } from '@/Stores/ChatStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import EmojiPicker from '@/Components/Global/Text/EmojiPicker.vue'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()
const chatStore = useChatStore()
const notificationStore = useNotificationStore()

const messageInput = ref(null)
const errorMessage = ref('')

let props = defineProps({
  user: Object,
})

let form = useForm({
  user_name: props.user.name,
  user_profile_photo_path: props.user.profile_photo_path,
  user_profile_photo_url: props.user.profile_photo_url,
})

// Watch the form.message for changes
watch(() => chatStore.message, (newValue) => {
  chatStore.inputTooLong = newValue.length > 300
})


const showEmojiPicker = ref(false)

// const toggleEmojiPicker = (event) => {
//   if (event instanceof MouseEvent) {
//     showEmojiPicker.value = !showEmojiPicker.value
//   }
// }
// const addEmoji = (emoji) => {
//   form.message += emoji
//   chatStore.closeEmojiPicker()
// }

const vFocus = {
  mounted: (el) => el.focus(),
}

function sendMessage() {
  showEmojiPicker.value = false
  if (chatStore.cooldown) {
    chatStore.setErrorMessage('')
    chatStore.setErrorMessage('Whoa there, speedy! Let\'s give others a chance to chime in. Try sending your message in a bit. 🕒')
    return
  }

  if (chatStore.message === '') {
    chatStore.setErrorMessage('Looks like you\'re sending an invisible message! 🕵️‍♂️ Please type something to share with the community.')
    return
  }

  if (chatStore.message.length > 300) {
    chatStore.setErrorMessage('')
    notificationStore.setGeneralServiceNotification('Keep it short and sweet!', 'Messages must be 300 characters or shorter. 😊')
    return
  }

  chatStore.setErrorMessage('')

  axios.post('/chat/message', {
    message: chatStore.message,
    channel_id: chatStore.currentChannel.id,
    user_name: form.user_name,
    user_profile_photo_path: form.user_profile_photo_path,
    user_profile_photo_url: form.user_profile_photo_url,
    // Add other necessary fields
  }).then(response => {
    if (response.status === 201) {
      chatStore.message = ''
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

const closeEmojiPicker = (event) => {
  if (
      chatStore.showEmojiPicker &&
      !event.target.closest('.emoji-picker') &&
      !event.target.closest('button')
  ) {
    chatStore.closeEmojiPicker()
  }
}

onMounted(() => {
  document.addEventListener('click', closeEmojiPicker)
})

onUnmounted(() => {
  document.removeEventListener('click', closeEmojiPicker)
})
</script>
