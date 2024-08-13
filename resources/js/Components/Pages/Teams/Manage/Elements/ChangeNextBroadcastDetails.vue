<template>
  <div>
    <button @click="openModal"
            class="mt-2 btn bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 ease-in-out hover:cursor-pointer shadow-md">
      Set Next Broadcast Zoom Link
    </button>

    <dialog id="changeNextBroadcastDetailsModal" class="modal">
      <div class="modal-box w-full max-w-2xl items-center text-center bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 rounded-lg shadow-lg">
        <form method="dialog">
          <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">✕</button>
        </form>

        <div v-if="!successMessage && !errorMessage" class="select-next-broadcast p-6 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg shadow-lg">
          <label v-if="filteredBroadcasts.length > 0" for="broadcast-select" class="block mb-4 text-lg font-semibold text-white">
            Select Next Broadcast:
          </label>
          <select v-if="filteredBroadcasts.length > 0" id="broadcast-select" v-model="selectedBroadcast"
                  class="select w-full max-w-xs p-3 rounded-lg text-gray-800 bg-white dark:bg-gray-700 dark:text-gray-200 shadow focus:outline-none focus:ring-2 focus:ring-indigo-300 transition ease-in-out duration-200">
            <option disabled value="">Choose Broadcast date</option>
            <option v-for="broadcast in filteredBroadcasts" :key="broadcast.scheduleIndexId"
                    :value="broadcast">
              {{ broadcast.name }} - {{ formatCompactDateTime(broadcast.broadcastDate) }}
            </option>
          </select>
          <p v-else class="text-lg font-semibold text-gray-100 dark:text-gray-300">
            No Broadcasts are currently scheduled.
          </p>
          <div v-if="selectedBroadcast" class="my-6 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ selectedBroadcast.name }}</h3>
            <p class="mt-2 text-sm italic text-gray-600 dark:text-gray-400">Zoom Link:</p>
            <input v-model="zoomLink" type="text" placeholder="Type here"
                   class="input w-full max-w-xs mt-2 p-3 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition ease-in-out duration-200"/>
          </div>
        </div>

        <div v-else class="py-4 text-xl mt-4 font-medium tracking-wide">
          <span class="text-green-500">{{ successMessage }}</span>
          <span class="text-red-500">{{ errorMessage }}</span>
        </div>

        <button v-if="!successMessage && !errorMessage && filteredBroadcasts.length > 0"
                @click.prevent="saveBroadcastDetails"
                class="mt-4 btn bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 ease-in-out shadow-md">
          Save
        </button>
        <button v-else
                @click="handleOkayClick"
                class="mt-4 btn bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 ease-in-out shadow-md">
          Okay
        </button>
      </div>

      <form method="dialog" class="modal-backdrop">
        <button class="text-white">Close</button>
      </form>
    </dialog>
  </div>
</template>



<script setup>
import { ref, computed } from 'vue'
import { useTeamStore } from '@/Stores/TeamStore'
import { useUserStore } from '@/Stores/UserStore'
import dayjs from 'dayjs'
import utc from 'dayjs-plugin-utc'
import timezone from 'dayjs/plugin/timezone'

dayjs.extend(utc)
dayjs.extend(timezone)

const teamStore = useTeamStore()
const userStore = useUserStore()

const successMessage = ref('')
const errorMessage = ref('')
const hasError = ref(false) // New state variable to track if there was an error

// Selected broadcast from the dropdown
const selectedBroadcast = ref('')

// Computed property for the zoom link
const zoomLink = computed({
  get() {
    return selectedBroadcast.value?.broadcastDetails?.zoomLink || ''
  },
  set(value) {
    if (selectedBroadcast.value && selectedBroadcast.value.broadcastDetails) {
      selectedBroadcast.value.broadcastDetails.zoomLink = value
    }
  },
})

const formatCompactDateTime = (date) => {
  return dayjs(date).tz(userStore.timezone).format('MMM D, YYYY, h:mm a');
};

const filteredBroadcasts = computed(() => {
  const now = dayjs().tz(userStore.timezone);
  const threeHoursAgo = now.subtract(3, 'hour');

  return teamStore.team.nextBroadcast.filter(broadcast => {
    const broadcastDate = dayjs(broadcast.broadcastDate).tz(userStore.timezone);
    return broadcastDate.isAfter(threeHoursAgo);
  });
});

// Save Broadcast Details function
const saveBroadcastDetails = () => {
  const payload = {
    scheduleIndexId: selectedBroadcast.value.scheduleIndexId,
    nextBroadcastZoomLink: zoomLink.value,
  }

  axios.post('/teams/' + teamStore.team.slug + '/save-broadcast-details', payload)
      .then(response => {
        successMessage.value = response.data.message  // assuming your API returns a message
        errorMessage.value = ''
        hasError.value = false // Reset the error state on success
        // Add any additional logic here, e.g., closing a modal, etc.
      })
      .catch(error => {
        // Check if the error response contains validation errors under 'errors'
        if (error.response && error.response.data && error.response.data.errors) {
          const errors = error.response.data.errors
          const firstError = Object.keys(errors)[0] // Get the first error key
          errorMessage.value = errors[firstError][0] // Get the first error message of the first key
        } else if (error.response && error.response.data && error.response.data.message) {
          errorMessage.value = error.response.data.message
        } else {
          errorMessage.value = 'An unknown error occurred'
        }
        hasError.value = true // Set the error state on failure
      })
}

const openModal = () => {
  document.getElementById('changeNextBroadcastDetailsModal').showModal()
}

const closeModal = () => {
  document.getElementById('changeNextBroadcastDetailsModal').close()
  // Wait 500 milliseconds before clearing the success and error messages
  setTimeout(() => {
    successMessage.value = '';
    errorMessage.value = '';
    hasError.value = false; // Reset the error state when closing the modal
  }, 500);
}

const handleOkayClick = () => {
  if (hasError.value) {
    // If there was an error, try submitting again
    // savePublicMessage();
    errorMessage.value = ''
    hasError.value = false
  } else {
    // If no error, close the modal
    closeModal()
  }
}
</script>

<style scoped>
.select-next-broadcast {
  max-width: 400px;
  margin: 0 auto;
  transition: all 0.3s ease-in-out;
}
</style>
