<template>
  <div>
    <button @click="openForm" class="flex items-center justify-center bg-green-500 text-white p-4 rounded-lg shadow-md hover:bg-green-600 transition">
      📤 Upload Press Release
    </button>

    <div v-if="showForm" :class="modalClass">
      <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full mx-4 lg:mx-0">
        <h2 class="text-xl font-bold mb-4">Upload Press Release</h2>
        <form @submit.prevent="submitForm">
          <div v-if="errors.length" class="mb-4">
            <ul class="list-disc list-inside text-red-500">
              <li v-for="error in errors" :key="error">{{ error }}</li>
            </ul>
          </div>
          <div class="mb-4">
            <label for="name" class="block text-gray-700">Your Name</label>
            <input type="text" id="name" v-model="form.name" class="w-full p-2 border border-gray-300 rounded-md">
          </div>
          <div class="mb-4">
            <label for="email" class="block text-gray-700">Your Email</label>
            <input type="email" id="email" v-model="form.email" class="w-full p-2 border border-gray-300 rounded-md">
          </div>
          <div class="mb-4">
            <label for="file" class="block text-gray-700">Press Release File</label>
            <input type="file" id="file" @change="handleFileUpload" class="w-full p-2 border border-gray-300 rounded-md">
          </div>
          <div class="flex justify-between items-center">
            <button type="button" @click="closeForm" class="bg-gray-500 text-white p-2 rounded-md hover:bg-gray-600 transition">Cancel</button>
            <button type="submit" class="bg-green-500 text-white p-2 rounded-md hover:bg-green-600 transition">Upload</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'

const showForm = ref(false)
const form = ref({
  name: '',
  email: '',
  file: null
})
const errors = ref([])

const page = usePage().props

const openForm = () => {
  showForm.value = true
}

const closeForm = () => {
  showForm.value = false
  form.value = {
    name: '',
    email: '',
    file: null
  }
  errors.value = []
}

const handleFileUpload = (event) => {
  form.value.file = event.target.files[0]
}

const submitForm = async () => {
  const formData = new FormData()
  formData.append('name', form.value.name)
  formData.append('email', form.value.email)
  formData.append('file', form.value.file)

  try {
    await axios.post('/upload-press-release', formData)
    alert('Your press release has been uploaded successfully.')
    closeForm()
  } catch (error) {
    // console.error('Failed to upload press release:', error)
    if (error.response && error.response.data && error.response.data.errors) {
      errors.value = Object.values(error.response.data.errors).flat()
    } else {
      alert('There was an error uploading your press release. Please try again.')
    }
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
