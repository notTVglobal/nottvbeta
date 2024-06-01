<template>
  <div>
    <button @click="openForm" class="flex items-center justify-center bg-indigo-500 text-white p-4 rounded-lg shadow-md hover:bg-indigo-600 transition">
      📝 Have a News Tip?
    </button>

    <div v-if="showForm" :class="modalClass">
      <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full mx-4 lg:mx-0">
        <h2 class="text-xl font-bold mb-4">Submit a News Tip</h2>
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
            <label for="phone" class="block text-gray-700">Your Phone</label>
            <input type="tel" id="phone" v-model="form.phone" class="w-full p-2 border border-gray-300 rounded-md">
          </div>
          <div class="mb-4">
            <label for="postalCode" class="block text-gray-700">Your Postal Code</label>
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

const showForm = ref(false)
const form = ref({
  name: '',
  email: '',
  phone: '',
  postalCode: '',
  message: ''
})

const page = usePage().props

const openForm = () => {
  showForm.value = true
}

const closeForm = () => {
  showForm.value = false
  form.value = {
    name: '',
    email: '',
    phone: '',
    postalCode: '',
    message: ''
  }
}

const submitForm = async () => {
  try {
    await axios.post('/news-tip', form.value)
    alert('Your news tip has been submitted successfully.')
    closeForm()
  } catch (error) {
    console.error('Failed to submit news tip:', error)
    alert('There was an error submitting your news tip. Please try again.')
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
