<template>
  <div class="flex flex-col gap-4">
    <div v-for="destination in goLiveStore.destinations" :key="destination.id" class="border p-4 rounded-lg shadow flex flex-col md:flex-row items-center gap-4">
      <img :src="destination.destination_image" alt="Destination Image" class="w-24 h-24 object-cover rounded-full" />
      <div class="flex-grow">
        <h3 class="text-lg font-semibold">{{ destination.destination_name }}</h3>
        <h4>{{ destination.comment }}</h4>
        <p v-if="destination.push_is_started" class="text-red-500 font-semibold">Push Is Active</p>
        <div class="flex flex-col xl:flex-row gap-2 mt-2">
          <button v-if="destination.push_is_started"
                  @click="goLiveStore.stopPush(destination.id, destination.mist_push_id)"
                  :disabled="goLiveStore.loadingDestinationId === destination.id"
                  class="py-2 px-4 text-white rounded transition duration-150 disabled:bg-gray-400 disabled:cursor-not-allowed"
                  :class="stopButtonClass">
            Stop Push
          </button>
          <button v-else
                  @click="goLiveStore.startPush(destination.id, destination.full_push_uri, destination.mist_push_id)"
                  :disabled="goLiveStore.loadingDestinationId === destination.id"
                  class="py-2 px-4 text-white rounded transition duration-150 disabled:bg-gray-400 disabled:cursor-not-allowed"
                  :class="startButtonClass">
            Start Push
          </button>
          <button v-if="!destination.has_auto_push"
                  @click="goLiveStore.enableAutoPush(destination.id)"
                  :disabled="goLiveStore.loadingDestinationId === destination.id"
                  class="py-2 px-4 text-white rounded transition duration-150 disabled:bg-gray-400 disabled:cursor-not-allowed"
                  :class="autoPushButtonClass">
            Enable Auto Push
          </button>
          <button v-if="destination.has_auto_push"
                  @click="goLiveStore.disableAutoPush(destination.id)"
                  :disabled="goLiveStore.loadingDestinationId === destination.id"
                  class="hidden py-2 px-4 text-white rounded transition duration-150 disabled:bg-gray-400 disabled:cursor-not-allowed">
            Disable Auto Push
          </button>
          <p v-if="destination.has_auto_push" class="text-yellow-500 font-semibold">Auto push is enabled</p>
          <span v-if="goLiveStore.loadingDestinationId === destination.id" class="loading loading-spinner text-info"></span>
        </div>
      </div>
      <div class="flex flex-row flex-wrap justify-end">
        <button @click.prevent="editDestination(destination)" class="btn btn-sm text-white font-semibold rounded-lg" :class="editButtonClass">
          <font-awesome-icon icon="fa-pencil" class="my-1 mx-1" />
        </button>
      </div>
      <button @click.prevent="goLiveStore.deleteDestination(destination.id)" class="btn btn-sm text-white font-semibold rounded-lg" :class="deleteButtonClass">
        <font-awesome-icon icon="fa-trash-can" class="my-1 mx-1" />
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'

const goLiveStore = useGoLiveStore()
const appSettingStore = useAppSettingStore()

const stopButtonClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'bg-red-500 hover:bg-red-700' : 'bg-red-500 hover:bg-red-700'
})

const startButtonClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'bg-blue-500 hover:bg-blue-700' : 'bg-blue-500 hover:bg-blue-700'
})

const autoPushButtonClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-yellow-500 hover:bg-yellow-600'
})

const editButtonClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'bg-blue-500 hover:bg-blue-600' : 'bg-blue-500 hover:bg-blue-600'
})

const deleteButtonClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'bg-red-500 hover:bg-red-600' : 'bg-red-500 hover:bg-red-600'
})

const editDestination = async (destination) => {
  goLiveStore.mistStreamPushDestinationFormModalMode = 'edit'
  goLiveStore.destinationDetails = destination
  document.getElementById('mistStreamPushDestinationForm').showModal()
  // console.log(`Editing destination with ID: ${destination}`)
  // const index = mistStreamPushDestinations.value.findIndex(destination => destination.id === destinationDetails.value.id)
  // if (index !== -1) {
  //   mistStreamPushDestinations.value[index].has_auto_push = 0
  // }
}


</script>
