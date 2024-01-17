<template>
  <Head title="Stream"/>
</template>

<script setup>
import { inject, onMounted, ref } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'

usePageSetup('stream')

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()

const getUserData = inject('getUserData', null)

appSettingStore.osd = true
videoPlayerStore.makeVideoFullPage()

onMounted(async () => {
  if (!getUserData) {
    updateUserStore()
  }
})

let props = defineProps({
  getUserData: Boolean,
  video: Object,
  user: Object,
})

function updateUserStore() {
  axios.post('/getUserStoreData')
      .then(response => {
        userStore.id = response.data.id
        userStore.loggedIn = true
        userStore.isAdmin = response.data.isAdmin
        userStore.isCreator = response.data.isCreator
        userStore.isNewsPerson = response.data.isNewsPerson
        userStore.isVip = response.data.isVip
        userStore.isSubscriber = response.data.isSubscriber
        userStore.hasAccount = response.data.hasAccount
        userStore.getUserDataCompleted = true
        userStore.timezone = userTimezone
        console.log('get user data on Stream')
        if (userStore.isCreator) {
          userStore.prevUrl = '/dashboard'
        } else {
          userStore.prevUrl = '/stream'
        }
      })
      .catch(error => {
        console.log(error)
      })
  // save user Timezone
  updateUserTimezone()
}

const userTimezone = ref('')

const getUserTimezone = () => {
  // Use the Intl object to get the user's timezone
  userTimezone.value = Intl.DateTimeFormat().resolvedOptions().timeZone
}

const updateUserTimezone = async () => {
  try {
    const response = await axios.post('/users/update-timezone', {timezone: userTimezone.value})

    // Handle success response as needed
    console.log(response.data.message)
  } catch (error) {
    // Handle error response or network error
    console.error(error)

    if (error.response) {
      // Handle specific error responses if needed
      console.error(error.response.data)
    }
  }
}
</script>

