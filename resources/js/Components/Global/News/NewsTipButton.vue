<template>
  <div>
    <button @click="openForm" class="flex items-center justify-center bg-indigo-500 text-white p-4 rounded-lg shadow-md hover:bg-indigo-600 transition">
      <span v-if="!newsPersonId">📝 Have a News Tip?</span>
      <span v-else>✉️ Send {{ newsPersonName }}<br/>a Message</span>
    </button>

    <div v-if="showForm" :class="modalClass">
      <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full mx-4 lg:mx-0">
        <h2 class="text-xl font-bold mb-4">
          <span v-if="!newsPersonId">Submit a News Tip</span>
          <span v-else>Send {{ newsPersonName }} a Message</span>
        </h2>
        <p class="text-sm text-gray-600 mb-4">This is an encrypted message that does not go through email.</p>
        <form @submit.prevent="submitForm">
          <div class="mb-4">
            <label for="name" class="block text-gray-700">Your Name</label>
            <input type="text" id="name" v-model="form.name" class="w-full p-2 border border-gray-300 rounded-md">
          </div>
          <div class="mb-4">
            <label for="email" class="block text-gray-700">Your Email</label>
            <input type="email" id="email" v-model="form.email" class="w-full p-2 border border-gray-300 rounded-md">
          </div>
          <div class="mb-4">
            <label for="phone" class="block text-gray-700">Your Phone <span class="text-gray-500">(optional)</span></label>
            <input type="tel" id="phone" v-model="form.phone" class="w-full p-2 border border-gray-300 rounded-md">
          </div>
          <div class="mb-4">
            <label for="postalCode" class="block text-gray-700">Your Postal Code <span class="text-gray-500">(optional)</span></label>
            <input type="text" id="postalCode" v-model="form.postalCode" class="w-full p-2 border border-gray-300 rounded-md">
          </div>
          <div class="mb-4">
            <label for="message" class="block text-gray-700">Your Message</label>
            <textarea id="message" v-model="form.message" class="w-full p-2 border border-gray-300 rounded-md"></textarea>
          </div>
          <div class="flex justify-between items-center">
            <button type="button" @click="closeForm" class="bg-gray-500 text-white p-2 rounded-md hover:bg-gray-600 transition">Cancel</button>
            <button type="submit" class="bg-indigo-500 text-white p-2 rounded-md hover:bg-indigo-600 transition">Send Tip</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useNotificationStore } from '@/Stores/NotificationStore'

const notificationStore = useNotificationStore()

const props = defineProps({
  newsPersonId: Number || null,
  newsPersonName: String || null,
})

const showForm = ref(false)
const form = ref({
  name: '',
  email: '',
  phone: '',
  postalCode: '',
  message: '',
  news_person_id: null,
})

const page = usePage().props

const openForm = () => {
  showForm.value = true
  if (props.newsPersonId) {
    form.value.news_person_id = props.newsPersonId
  }
}

const closeForm = () => {
  showForm.value = false
  form.value = {
    name: '',
    email: '',
    phone: '',
    postalCode: '',
    message: '',
    news_person_id: null
  }
}

const submitForm = async () => {
  let messageType = 'news tip'
  if (props.newsPersonId) {
    form.news_person_id = props.newsPersonId
    messageType = 'message'
  }

  try {
    await axios.post('/news-tip', form.value)
    console.log(form.value)
    notificationStore.setGeneralServiceNotification('Success', 'Your ' + messageType + ' has been submitted successfully.')
    closeForm()
  } catch (error) {
    console.error('Failed to submit news tip.')
    notificationStore.setGeneralServiceNotification('Error', 'There was an error submitting your ' + messageType + '. Please try again.')
  }
}

const modalClass = computed(() => {
  return {
    'fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50': true,
    'lg:mr-96': page.auth.user
  }
})
</script>

<style scoped>
button {
  font-size: 1.125rem; /* text-lg */
  font-weight: 600; /* font-semibold */
}

div {
  font-size: 1rem; /* text-base */
}
</style>
