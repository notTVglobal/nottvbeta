<template>
  <div>
    <button @click="openDialog" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
      Get an Email Reminder
    </button>
    <div v-if="isDialogOpen"
         class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-998"
         @click.self="closeDialog">
      <div class="bg-white rounded-lg p-6 w-96 shadow-lg z-999">
        <form @submit.prevent="sendEmailReminder">
          <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email:</label>
          <input type="email" v-model="email"
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                 required/>
          <div class="mt-4 flex justify-end gap-2">
            <button type="submit" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
              <div class="flex items-center gap-2"><img src="/storage/images/Ping.png" class="w-4 h-4"/> Send Reminder
              </div>
            </button>
            <button type="button" @click="closeDialog"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Cancel
            </button>

          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { useTeamStore } from '@/Stores/TeamStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'

const email = ref('')
const isDialogOpen = ref(false)
const teamStore = useTeamStore()
const notificationStore = useNotificationStore()
const page = usePage()

onMounted(() => {
  if (page.props.auth.user) {
    email.value = page.props.auth.user.email
  }
})

const openDialog = () => {
  isDialogOpen.value = true
  document.addEventListener('keydown', handleKeyDown)
}

const closeDialog = () => {
  isDialogOpen.value = false
  document.removeEventListener('keydown', handleKeyDown)
}

const handleKeyDown = (event) => {
  if (event.key === 'Escape') {
    closeDialog()
  }
}

const sendEmailReminder = async () => {
  const eventDetails = {
    broadcastDate: teamStore.nextBroadcast.broadcastDate,
    name: teamStore.nextBroadcast.name,
    image: teamStore.nextBroadcast.image.cdn_endpoint + 'cloud_folder' + 'folder' + '/' + teamStore.nextBroadcast.image.name,
    description: teamStore.nextBroadcast.description,
    url: page.props.appUrl + page.props.currentPath,
    category: {
      name: teamStore.nextBroadcast.category.name,
    },
    subCategory: {
      name: teamStore.nextBroadcast.subCategory.name,
    },
  }

  await axios.post('/send-email-reminder', {
    email: email.value,
    eventDetails,
  })
  notificationStore.setGeneralServiceNotification('Reminder Set!', 'You’re all set! We’ll send you an email reminder before the broadcast starts. Stay tuned!')
  closeDialog()
}

onBeforeUnmount(() => {
  document.removeEventListener('keydown', handleKeyDown)
})
</script>

<style scoped>
.dialog-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(5px);
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
