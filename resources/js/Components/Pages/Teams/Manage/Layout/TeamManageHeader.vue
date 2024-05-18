<template>


  <div>
    <div class="flex justify-between mb-8 pt-6">
      <div class="inline-flex items-center text-3xl font-semibold relative">

        <SingleImage :image="image" :alt="'team logo'" :class="'w-20 mr-2'"/>

        <Link :href="`/teams/${team.slug}`" class="uppercase text-black">{{ team.name }}</Link>
        <div
            class="bg-green-400 w-5 h-5 text-xs text-white rounded-full flex justify-center items-center absolute -left-3 -top-0.5">
          {{ teamStore.membersCount }}
        </div>
      </div>
      <div>
        <button @click="openEditPublicMessageModal" class="btn bg-yellow-500 hover:bg-yellow-600 text-black hover:cursor-pointer">
          Change Public Message
        </button>
      </div>
    </div>
    <div class="flex justify-between text-black">

      <div><span class="text-xs font-semibold mr-2 uppercase">Team Leader: </span>
        <span class="font-bold" v-if="teamLeader.name">{{ teamLeader.name }}</span>
        <span v-else>No team leader assigned</span>
      </div>
      <div class="w-1/2 lg:w-1/3">
        <div class="uppercase text-sm">Public Message:</div>
        <span v-html="publicMessage"/>
      </div>
    </div>


    <dialog id="editPublicMessageModal" class="modal">
      <div class="modal-box w-full items-center text-center bg-white dark:bg-gray-800 dark:text-white">
        <form method="dialog">
          <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg">Change Your Public Message</h3>
        <div v-if="!successMessage && !errorMessage" class="py-4 text-xl mt-4 font-medium tracking-wide">
          <textarea v-model="publicMessage"
                    class="textarea textarea-bordered h-40 w-full p-4 bg-white dark:bg-gray-800 dark:text-white"
                    placeholder="Type your public message here..."
                    rows="4"></textarea>
          <div :class="{'text-red-500': publicMessage?.length > 440, 'text-gray-800': publicMessage?.length <= 440}"
               class="mt-2 text-left text-xs font-light">
            {{ publicMessage?.length }}/440 max characters
          </div>
          <div>
            <p>insert zoom link</p>
            <input v-model="nextBroadcastDetails.zoomLink" type="text" placeholder="Type here"
                   class="input input-bordered w-full max-w-xs bg-white dark:bg-gray-800 dark:text-white"/>
            <p class="">for the next broadcast:</p>
            <p> {{ userStore.formatDateTimeFromUtcToUserTimezone(nextBroadcast?.broadcastDate) }}, {{ nextBroadcast?.name }}</p>
<!--            <select>-->
<!--              <option>Next broadcast</option>-->
<!--            </select>-->
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
import { useUserStore } from '@/Stores/UserStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useTeamStore } from '@/Stores/TeamStore'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'
import { computed, reactive, ref, watchEffect } from 'vue'

const userStore = useUserStore()
const appSettingStore = useAppSettingStore()
const teamStore = useTeamStore()

const props = defineProps({
  team: Object,
  teamLeader: Object,
  logo: String,
  image: Object,
  can: Object,
})


// Initialize publicMessage from props
const publicMessage = ref(props.team.public_message);

// Initialize nextBroadcast from props
const nextBroadcast = props.team.nextBroadcast[0];
const nextBroadcastDetails = reactive({})

if (nextBroadcast) {
  // Initialize nextBroadcastDetails with the zoomLink from props if it exists
  nextBroadcastDetails.value = reactive({
    zoomLink: nextBroadcast.broadcastDetails?.zoomLink || '',  // Use existing zoomLink or default to an empty string
  });

}

const successMessage = ref('')
const errorMessage = ref('')
const hasError = ref(false); // New state variable to track if there was an error

watchEffect(() => {
  publicMessage.value = props.team.public_message || ''
})

const membersCount = computed(() => {
  const count = props.team.members.length
  return count > 99 ? '99+' : count
})


const openEditPublicMessageModal = () => {
  document.getElementById('editPublicMessageModal').showModal()
}

const closePublicMessageModal = () => {
  document.getElementById('editPublicMessageModal').close()
  successMessage.value = ''
  errorMessage.value = ''
  hasError.value = false; // Reset the error state when closing the modal
}

const savePublicMessage = () => {
  const payload = {
    public_message: publicMessage.value,
  };

  // Always include next_broadcast_details and schedule_index_id in the payload
  payload.next_broadcast_details = JSON.stringify(nextBroadcastDetails);

  // Include schedule_index_id only if it exists
  if (nextBroadcast?.scheduleIndexId) {
    payload.schedule_index_id = nextBroadcast.scheduleIndexId;
  }

  // console.log(nextBroadcastDetails); // Check the structure
  // console.log(payload); // Verify the payload before sending
  axios.post(`/teams/${props.team.slug}/save-public-message`, payload)
      .then(response => {
        successMessage.value = response.data.message  // assuming your API returns a message
        errorMessage.value = '';
        hasError.value = false; // Reset the error state on success
        closePublicMessageModal(); // Close the modal on successful submission
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
        hasError.value = true; // Set the error state on failure
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
    closePublicMessageModal();
  }
};

</script>
