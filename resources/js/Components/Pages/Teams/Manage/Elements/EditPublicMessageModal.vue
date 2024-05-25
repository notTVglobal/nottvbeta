<template>
  <div>
    <button @click="openEditPublicMessageModal"
            class="btn bg-yellow-500 hover:bg-yellow-600 text-black hover:cursor-pointer">
      Change Public Message
    </button>
    <dialog id="editPublicMessageModal" class="modal">
      <div class="modal-box w-full items-center text-center bg-white dark:bg-gray-800 dark:text-white">
        <form method="dialog">
          <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg">Change Your Public Message</h3>
        <div v-if="!successMessage && !errorMessage" class="py-4 text-xl mt-4 font-medium tracking-wide">
          <tip-tap-description-editor
              :placeholder="`Start typing the public message...`"
              :description="team?.public_message"
              @updateContent="handleContentUpdate"
          />
          <div
              :class="{'text-red-500': team?.public_message?.length > 440, 'text-gray-800': team?.public_message?.length <= 440}"
              class="mt-2 text-left text-xs font-light">
            {{ team?.public_message?.length }}/440 max characters
          </div>
          <div v-if="hasNextBroadcastDate">
            <p class="italic">insert zoom link</p>
            <input v-model="zoomLink" type="text" placeholder="Type here"
                   class="input input-bordered w-full max-w-xs bg-white dark:bg-gray-800 dark:text-white border-black focus:border-black"/>
            <p class="mt-2 text-sm uppercase font-semibold">
              for the next broadcast:</p>
            <p class="">{{ formatedBroadcastDate }}</p>
            <p class="font-semibold uppercase">{{ teamStore.nextBroadcast?.name }}</p>
          </div>
        </div>
        <div v-else class="py-4 text-xl mt-4 font-medium tracking-wide">
          <span class="text-green-500">{{ successMessage }}</span>
          <span class="text-red-500">{{ errorMessage }}</span>
        </div>

        <button v-if="!successMessage && !errorMessage" class="mt-4 btn" @click="savePublicMessage">Save</button>
        <button v-else class="mt-4 btn" @click="handleOkayClick">
          Okay
        </button>
      </div>

      <form method="dialog" class="modal-backdrop">
        <button>close</button>
      </form>
    </dialog>
  </div>
</template>
<script setup>
import { computed, ref } from 'vue'
import { useTeamStore } from '@/Stores/TeamStore'
import { useUserStore } from '@/Stores/UserStore'
import dayjs from 'dayjs'
import timezone from 'dayjs/plugin/timezone'
import TipTapDescriptionEditor from '@/Components/Global/TextEditor/TipTapDescriptionEditor.vue'

dayjs.extend(timezone)

const teamStore = useTeamStore()
const userStore = useUserStore()

// Map store state to local computed properties
const team = computed(() => teamStore.team || {})

const successMessage = ref('')
const errorMessage = ref('')
const hasError = ref(false) // New state variable to track if there was an error

// Define the computed property for formatting broadcast date
const formatedBroadcastDate = computed(() => {
  return teamStore.nextBroadcast?.broadcastDate
      ? dayjs(teamStore.nextBroadcast?.broadcastDate).format('dddd MMMM DD [at] HH:mm a')
      : ''
})

const hasNextBroadcastDate = computed(() => !!teamStore.nextBroadcast.broadcastDate)


const zoomLink = computed({
  get() {
    const nextBroadcast = team.value?.nextBroadcast?.find(b => b.id === teamStore.nextBroadcast?.id);
    if (nextBroadcast && Array.isArray(nextBroadcast.broadcastDetails)) {
      const zoomLinkObj = nextBroadcast.broadcastDetails.find(detail => detail.zoomLink);
      return zoomLinkObj ? zoomLinkObj.zoomLink : '';
    }
    return '';
  },
  set(value) {
    const nextBroadcast = team.value?.nextBroadcast?.find(b => b.id === teamStore.nextBroadcast?.id);
    if (nextBroadcast) {
      if (!Array.isArray(nextBroadcast.broadcastDetails)) {
        nextBroadcast.broadcastDetails = [];
      }
      const zoomLinkObj = nextBroadcast.broadcastDetails.find(detail => detail.key === 'zoomLink');
      if (zoomLinkObj) {
        zoomLinkObj.value = value;
      } else {
        nextBroadcast.broadcastDetails.push({ key: 'zoomLink', value });
      }
    }
  }
});

const openEditPublicMessageModal = () => {
  document.getElementById('editPublicMessageModal').showModal()
}

const closePublicMessageModal = () => {
  document.getElementById('editPublicMessageModal').close()
  successMessage.value = ''
  errorMessage.value = ''
  hasError.value = false // Reset the error state when closing the modal
}

const handleContentUpdate = (html) => {
  teamStore.updatePublicMessage(html)
}

const savePublicMessage = () => {
  const payload = {
    public_message: team.value.public_message,
  }

  if (teamStore.nextBroadcast) {
    if (!teamStore.nextBroadcast.scheduleIndexId) {
      errorMessage.value = 'No schedule index found. Error Code: VUCS001'
      return
    }
    // Ensure nextBroadcastDetails exists and merge the zoomLink
    const nextBroadcast = team.value?.nextBroadcast?.find(b => b.id === teamStore.nextBroadcast?.id)
    let nextBroadcastDetails = nextBroadcast?.broadcastDetails || []

    // Ensure all elements are in the correct format by filtering out old key-value pairs
    nextBroadcastDetails = nextBroadcastDetails.filter(detail => !detail.key);

    // Add the new zoomLink
    nextBroadcastDetails.push({ zoomLink: zoomLink.value });

    payload.schedule_index_id = teamStore.nextBroadcast.scheduleIndexId;
    payload.next_broadcast_details = JSON.stringify(nextBroadcastDetails);  // Convert to JSON string
  }



  // console.log(nextBroadcastDetails); // Check the structure
  // console.log(payload); // Verify the payload before sending
  axios.post('/teams/' + team.value.slug + '/save-public-message', payload)

      .then(response => {
        successMessage.value = response.data.message  // assuming your API returns a message
        errorMessage.value = ''
        hasError.value = false // Reset the error state on success
        closePublicMessageModal() // Close the modal on successful submission
      })
      .catch(error => {
        // Check if the error response contains validation errors under 'errors'
        if (error.response && error.response.data && error.response.data.errors) {
          // Extract the first error message from each field
          const errors = error.response.data.errors
          const firstError = Object.keys(errors)[0] // Get the first error key
          errorMessage.value = errors[firstError][0] // Get the first error message of the first key
        } else if (error.response && error.response.data && error.response.data.message) {
          // Fallback to a general error message if it's not a validation error
          errorMessage.value = error.response.data.message
        } else {
          // Handle unexpected errors without a clear message
          errorMessage.value = 'An unknown error occurred'
        }
        hasError.value = true // Set the error state on failure
      })
}

const handleOkayClick = () => {
  if (hasError.value) {
    // If there was an error, try submitting again
    // savePublicMessage();
    errorMessage.value = ''
    hasError.value = false
  } else {
    // If no error, close the modal
    closePublicMessageModal()
  }
}

</script>