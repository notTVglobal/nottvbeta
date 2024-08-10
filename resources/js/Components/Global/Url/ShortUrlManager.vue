<template>
  <div>
    <button class="btn btn-primary btn-sm mt-2" @click.prevent="openModal">Set/Change Short URL</button>

    <dialog id="shortUrlManagerModal" class="modal">
      <div class="modal-box bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white rounded-lg shadow-lg">

        <h2 class="text-2xl font-bold mb-4">Set/Change Short URL</h2>

        <form @submit.prevent="saveShortUrl" class="space-y-4">
          <div>
            <label for="customName" class="block text-sm font-medium mb-1">
              Custom Name: <span class="text-sm text-gray-300">{{ shortUrlPreview }}</span>
            </label>
            <input v-model="customName" id="customName" class="input input-bordered w-full bg-gray-100 text-gray-800 rounded-md px-4 py-2" placeholder="Enter custom name" required/>
          </div>

          <div>
            <label for="originalUrl" class="block text-sm font-medium mb-1">Original URL:</label>
            <input v-model="originalUrl" id="originalUrl" class="input input-bordered w-full bg-gray-100 text-gray-800 rounded-md px-4 py-2" placeholder="Enter original URL" required/>
          </div>

          <button type="submit" class="btn btn-success w-full py-2 mt-4">Save</button>
        </form>

        <div class="modal-action flex justify-between items-center mt-6">
          <form method="dialog">
            <!-- if there is a button in form, it will close the modal -->
            <button class="btn btn-outline btn-white" @click="closeModal">Close</button>
            <button class="btn btn-sm btn-circle btn-ghost text-white hover:bg-white hover:text-indigo-600 absolute right-2 top-2">✕</button>
          </form>
        </div>

        <!-- Display Short URL Information -->
        <div v-if="shortUrlData" class="mt-6">
          <div class="flex flex-row">
            <strong>Short URL:</strong> &nbsp; <a :href="shortUrl" class="text-blue-300 underline">{{ shortUrl }}</a>
            <CopyClipboard :text="shortUrl" :buttonColor="`yellow`" :labelPosition="`-top-10 -left-20`"/>
          </div>
          <p>
            <strong>Clicks:</strong> {{ shortUrlData.clicks }}
            <button @click.prevent="resetClicks" class="text-sm text-blue-300 underline ml-2">Reset</button>
          </p>
          <p>
            <strong>Active:</strong> &nbsp;
            <span :class="{'text-green-400': shortUrlData.is_active, 'text-red-400': !shortUrlData.is_active}">
              {{ shortUrlData.is_active ? 'Yes' : 'No' }}
            </span>
            <button @click.prevent="toggleActiveStatus" class="text-sm text-blue-300 underline ml-2">
              {{ shortUrlData.is_active ? 'Disable' : 'Enable' }}
            </button>
          </p>
          <p><strong>Last Edited By:</strong> {{ shortUrlData.user ? shortUrlData.user.name : 'N/A' }}</p>
        </div>
        <div v-else class="mt-6 text-yellow-300">
          <p>No short URL set for this show yet.</p>
        </div>
      </div>
    </dialog>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useShowStore } from '@/Stores/ShowStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import CopyClipboard from '@/Components/Global/Text/CopyClipboard.vue'

const showStore = useShowStore()
const notificationStore = useNotificationStore()

const customName = ref('')
const originalUrl = ref('')
const shortUrlData = ref(null)

// Open the modal
const openModal = () => {
  document.getElementById('shortUrlManagerModal').showModal()
  // Load the current short URL data when the modal opens
  loadShortUrlData()
}

// Close the modal
const closeModal = () => {
  document.getElementById('shortUrlManagerModal').close()
}

// Load the current short URL data
const loadShortUrlData = async () => {
  try {
    const response = await axios.get(`/api/short-urls/${showStore.slug}`);
    shortUrlData.value = response.data;
    customName.value = shortUrlData.value.custom_name;
    originalUrl.value = shortUrlData.value.original_url;
  } catch (error) {
    if (error.response && error.response.status === 404) {
      notificationStore.setToastNotification('No short URL set yet.', 'warning')
      shortUrlData.value = null; // No short URL set yet
      customName.value = ''; // Reset the custom name field
      originalUrl.value = ''; // Reset the original URL field
    } else {
      // Check if the error has a response and message
      const errorMessage = error.response && error.response.data && error.response.data.error
          ? error.response.data.error
          : 'An unknown error occurred.';

      notificationStore.setGeneralServiceNotification('Error', errorMessage);
    }
  }
}

// Save or update the short URL
const saveShortUrl = async () => {
  if (!isValidUrl(originalUrl.value)) {
    notificationStore.setGeneralServiceNotification('Alert', 'Please enter a valid URL.')
    return
  }

  try {
    const response = await axios.post('/short-urls', {
      show_id: showStore.id,
      custom_name: customName.value,
      original_url: originalUrl.value,
    })

    shortUrlData.value = response.data
    closeModal()
  } catch (error) {
    // Check if the error has a response and message
    const errorMessage = error.response && error.response.data && error.response.data.error
        ? error.response.data.error
        : 'An unknown error occurred.';

    notificationStore.setGeneralServiceNotification('Error', errorMessage);
  }
}

// Reset the clicks count
const resetClicks = async () => {
  try {
    const response = await axios.post(`/short-urls/${showStore.slug}/reset-clicks`);
    shortUrlData.value.clicks = response.data.clicks;
    notificationStore.setToastNotification('Click count reset successfully.', 'success');
  } catch (error) {
    // Check if the error has a response and message
    const errorMessage = error.response && error.response.data && error.response.data.error
        ? error.response.data.error
        : 'An unknown error occurred.';

    notificationStore.setToastNotification(errorMessage, 'error');
  }
}

// Toggle the active status
const toggleActiveStatus = async () => {
  try {
    const response = await axios.post(`/short-urls/${showStore.slug}/toggle-active`);
    shortUrlData.value.is_active = response.data.is_active;
    notificationStore.setToastNotification(`Short URL is now ${response.data.is_active ? 'active' : 'disabled'}.`, 'success');
  } catch (error) {
    // Check if the error has a response and message
    const errorMessage = error.response && error.response.data && error.response.data.error
        ? error.response.data.error
        : 'An unknown error occurred.';

    notificationStore.setToastNotification(errorMessage, 'error');
  }
}

// Check if the URL is valid
const isValidUrl = (url) => {
  try {
    new URL(url)
    return true
  } catch (_) {
    return false
  }
}

// Get the full short URL
const shortUrl = computed(() => {
  return shortUrlData.value ? `${window.location.origin}/r/${shortUrlData.value.custom_name}` : ''
})

const shortUrlPreview = computed(() => {
  return `${window.location.origin}/r/${customName.value}`;
});

</script>
<style scoped>
.modal-box {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 0.5rem;
}

.input {
  border: none;
  outline: none;
  transition: background-color 0.2s ease-in-out;
}

.input:focus {
  background-color: #edf2f7;
}

.btn-primary {
  background-color: #5A67D8;
  color: #fff;
}

.btn-primary:hover {
  background-color: #434190;
}

.btn-success {
  background-color: #48BB78;
  color: #fff;
}

.btn-success:hover {
  background-color: #38A169;
}

.btn-outline {
  border: 1px solid #fff;
  color: #fff;
}

.btn-outline:hover {
  background-color: #fff;
  color: #5A67D8;
}
</style>