<template>


  <div>
    <div class="flex justify-between mb-8 pt-6">
      <div class="inline-flex items-center text-3xl font-semibold relative">

        <SingleImage :image="image" :alt="'team logo'" :class="'w-20 mr-2'"/>

        <Link :href="`/teams/${team.slug}`" class="uppercase text-black">{{ team.name }}</Link>
        <div
            class="bg-green-400 w-5 h-5 text-xs text-white rounded-full flex justify-center items-center absolute -left-3 -top-0.5">
          {{ membersCount }}
        </div>
      </div>
      <div>
        <button @click="openEditPublicMessageModal" class="btn bg-yellow-500 hover:bg-yellow-600 hover:cursor-pointer">
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
      <div class="modal-box w-full items-center text-center">
        <form method="dialog">
          <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg">Change Your Public Message</h3>
        <div v-if="!successMessage && !errorMessage" class="py-4 text-xl mt-4 font-medium tracking-wide">
          <textarea v-model="publicMessage"
                    class="textarea textarea-bordered h-40 w-full p-4"
                    placeholder="Type your public message here..."
                    rows="4"></textarea>
          <div :class="{'text-red-500': publicMessage?.length > 440, 'text-gray-800': publicMessage?.length <= 440}"
               class="mt-2 text-left text-xs font-light">
            {{ publicMessage?.length }}/440 max characters
          </div>
          <div>
            <p>insert zoom link</p>
            <input v-model="nextBroadcastDetails.zoomLink" type="text" placeholder="Type here" class="input input-bordered w-full max-w-xs" />
          </div>
        </div>
        <div v-else class="py-4 text-xl mt-4 font-medium tracking-wide">
          <span class="text-green-500">{{ successMessage }}</span>
          <span class="text-red-500">{{ errorMessage }}</span>
        </div>


        <button v-if="!successMessage && !errorMessage" class="mt-4 btn" @click="savePublicMessage">Save</button>
        <button v-else class="mt-4 btn" @click="closePublicMessageModal">
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
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'
import { computed, reactive, ref, watchEffect } from 'vue'

const appSettingStore = useAppSettingStore()

const props = defineProps({
  team: Object,
  teamLeader: Object,
  logo: String,
  image: Object,
  can: Object,
})

const publicMessage = ref(props.team.public_message)
const nextBroadcastDetails = reactive({
  zoomLink: ''  // Initialize zoomLink as an empty string
});

const successMessage = ref('')
const errorMessage = ref('')

watchEffect(() => {
  publicMessage.value = props.team.public_message || ''
})

const membersCount = computed(() => {
  const count = props.team.members.length;
  return count > 99 ? '99+' : count;
});



const openEditPublicMessageModal = () => {
  document.getElementById('editPublicMessageModal').showModal()
}

const closePublicMessageModal = () => {
  document.getElementById('editPublicMessageModal').close()
  successMessage.value = ''
  errorMessage.value = ''
}

const savePublicMessage = () => {
  const payload = {
    public_message: publicMessage.value,
    next_broadcast_details: JSON.stringify(nextBroadcastDetails)
  };
  // console.log(nextBroadcastDetails); // Check the structure
  // console.log(payload); // Verify the payload before sending
  axios.post(`/teams/${props.team.slug}/save-public-message`, payload)
      .then(response => {
        successMessage.value = response.data.message  // assuming your API returns a message
      })
      .catch(error => {
        // Check if the error response contains validation errors under 'errors'
        if (error.response && error.response.data && error.response.data.errors) {
          // Extract the first error message from each field
          const errors = error.response.data.errors;
          const firstError = Object.keys(errors)[0]; // Get the first error key
          errorMessage.value = errors[firstError][0]; // Get the first error message of the first key
        } else if (error.response && error.response.data && error.response.data.message) {
          // Fallback to a general error message if it's not a validation error
          errorMessage.value = error.response.data.message;
        } else {
          // Handle unexpected errors without a clear message
          errorMessage.value = 'An unknown error occurred';
        }
      });
}

</script>
