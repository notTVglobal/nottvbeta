<template>


  <div>
    <div class="flex justify-between mb-8 pt-6">
      <div class="inline-flex items-center text-3xl font-semibold relative">

        <SingleImage :image="image" :alt="'team logo'" :class="'w-20 mr-2'"/>

        <Link :href="`/teams/${team.slug}`" class="uppercase text-black">{{ team.name }}</Link>
        <div
            class="bg-green-400 w-5 h-5 text-xs text-white rounded-full flex justify-center items-center absolute -right-4 -top-0.5">
          {{ team.members.length }}
        </div>
      </div>
      <div>
        <button @click="openEditPublicMessageModal" class="btn bg-yellow-500 hover:bg-yellow-600 hover:cursor-pointer">Change Public Message</button>
      </div>
    </div>
    <div class="flex justify-between text-black">

      <div><span class="text-xs font-semibold mr-2 uppercase">Team Leader: </span>
        <span class="font-bold" v-if="teamLeader.name">{{ teamLeader.name }}</span>
        <span v-else>No team leader assigned</span>
      </div>
      <div class="w-1/2 lg:w-1/3">
        <div class="uppercase text-sm">Public Message:</div>
        <div>{{ publicMessage }}</div>
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
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"
import { ref, watchEffect } from 'vue'

const appSettingStore = useAppSettingStore()

const props = defineProps({
  team: Object,
  teamLeader: Object,
  logo: String,
  image: Object,
  can: Object,
})

const publicMessage = ref(props.team.public_message)
const successMessage = ref('')
const errorMessage = ref('')

watchEffect(() => {
  publicMessage.value = props.team.public_message || '';
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
  axios.post(`/teams/${props.team.slug}/save-public-message`, { public_message: publicMessage.value })
      .then(response => {
        successMessage.value = response.data.message;  // assuming your API returns a message
      })
      .catch(error => {
        errorMessage.value = error.response.data.message;  // handle error messages
      });
}

</script>
