<template>
  <div class="mx-4 mt-4 mb-2 px-6 py-1 ">

    <div
        class="text-sm font-semibold bg-blue-600 text-white text-center w-full border-2 border-blue-600 rounded uppercase px-6 py-1 ">
      Push Destinations
    </div>

    <div class="shadow bg-blue-100 overflow-hidden border-2 border-blue-600 rounded p-6 space-y-3">

      <div class="flex flex-row flex-wrap-reverse w-full justify-between">
        <div class="mb-2">
          <button @click="appSettingStore.btnRedirect('/training/how-to-push-to-facebook')"
                  class="btn btn-sm bg-blue-500 hover:bg-blue-700 rounded-lg text-white">How To Push To Facebook
          </button>
          <button @click="appSettingStore.btnRedirect('/training/how-to-push-to-rumble')"
                  class="btn btn-sm bg-blue-500 hover:bg-blue-700 rounded-lg text-white ml-2 ">How To Push To Rumble
          </button>
        </div>
        <div class="flex flex-col gap-2">
          <button class="btn bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg"
                  @click.prevent="addDestination">Add Push
            Destinations
          </button>
          <button
              v-if="anyDestinationHasActiveAutoPush"
              @click="goLiveStore.disableAllAutoPushes(goLiveStore.streamKey)"
              class="py-2 px-4 bg-gray-500 text-white rounded hover:bg-gray-600 transition duration-150"
          >
            Disable All Auto Pushes
          </button>
        </div>
      </div>


      <div class="flex flex-row justify-between mb-2 py-2 h-12">
        <div><h2 class="text-xl font-bold">Push Destinations</h2>
      </div>
        <div v-if="goLiveStore.isLoadingDestinations">
          <span class="loading loading-bars loading-lg text-info mr-2"> </span><span class="text-xs uppercase">Refreshing...</span>
        </div>
        </div>


      <div v-if="goLiveStore.destinations.length === 0">
        <div>Set up <span class="font-bold">push destinations:</span></div>
        <div>Here you can set additional streaming destinations such as Facebook, YouTube, Rumble, Twitch, etc. and
          notTV will automatically start pushing to those destinations when you go live.
        </div>
      </div>
      <div v-if="goLiveStore.destinations.length > 0">
        <div class="flex flex-col gap-4">
          <div v-for="destination in goLiveStore.destinations" :key="destination.id"
               class="border p-4 rounded-lg shadow flex flex-row items-center gap-4">
            <img :src="destination.destination_image" alt="Destination Image"
                 class="w-24 h-24 object-cover rounded-full"/>
            <div class="flex-grow">
              <h3 class="text-lg font-semibold">{{ destination.destination_name }}</h3>
              <h4 class="">{{ destination.comment }}</h4>

              <p v-if="destination.push_is_started" class="text-red-500 font-semibold">Push Is Active</p>
              <div class="flex gap-2 mt-2">
                <button v-if="destination.push_is_started"
                        @click="goLiveStore.stopPush(destination.id)"
                        class="py-2 px-4 bg-red-500 text-white rounded hover:bg-red-700 transition duration-150">
                  Stop Push
                </button>
                <button v-else
                        @click="goLiveStore.startPush(destination.id)"
                        class="py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-700 transition duration-150">
                  Start Push
                </button>
                <button v-if="!destination.has_auto_push"
                        @click="goLiveStore.enableAutoPush(destination.id)"
                        class="py-2 px-4 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition duration-150">
                  Enable Auto Push
                </button>
                <button v-if="destination.has_auto_push"
                        @click="goLiveStore.disableAutoPush(destination.id)"
                        class="hidden py-2 px-4 bg-gray-500 text-white rounded hover:bg-gray-600 transition duration-150">
                  Disable Auto Push
                </button>
                <p v-if="destination.has_auto_push" class="text-yellow-500 font-semibold">Auto push
                  is enabled</p>
                <span v-if="goLiveStore.loadingDestinationId === destination.id" class="loading loading-bars loading-lg text-info"></span>
              </div>
            </div>
            <div class="flex flex-row justify-end">
              <!--                  <button @click="editDestination(destination.id)" class="py-2 px-4 bg-green-500 text-white rounded hover:bg-green-600 transition duration-150">Edit</button>-->
              <!--                  <button @click="deleteDestination(destination.id)" class="py-2 px-4 bg-gray-500 text-white rounded hover:bg-gray-600 transition duration-150">Delete</button>-->

              <button
                  @click.prevent="editDestination(destination)"
                  class="btn btn-sm text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded-lg">
                <font-awesome-icon icon="fa-pencil" class="my-1 mx-1"/>
              </button>
            </div>
            <button
                @click.prevent="goLiveStore.deleteDestination(destination.id)"
                class="btn btn-sm text-white font-semibold bg-red-500 hover:bg-red-600 rounded-lg"
            >
              <font-awesome-icon icon="fa-trash-can" class="my-1 mx-1"/>
            </button>


          </div>
        </div>
      </div>
    </div>

    <MistStreamPushDestinationForm @update-success="mistStore.getMistStreamPushDestinations"
                                   :destinationDetails="destinationDetails"

                                   :mode="mistStreamPushDestinationFormModalMode"/>
    <ToastNotification />
  </div>
</template>
<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useMistStore } from '@/Stores/MistStore'
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import MistStreamPushDestinationForm from '@/Components/Global/MistStreams/MistStreamPushDestinationForm'
import { computed, ref } from 'vue'
import ToastNotification from '@/Components/Global/Notifications/Toast/ToastNotification.vue'

const appSettingStore = useAppSettingStore()
const mistStore = useMistStore()
const goLiveStore = useGoLiveStore()

const anyDestinationHasActiveAutoPush = computed(() => {
  return goLiveStore.destinations.some(destination => destination.has_auto_push === 1);
});

const addDestination = async () => {
  mistStreamPushDestinationFormModalMode.value = 'add'
  const wildcardId = goLiveStore.selectedShow?.mist_stream_wildcard?.id
  destinationDetails.value = {mist_stream_wildcard_id: wildcardId} // Initialize destinationDetails with the wildcard ID
  document.getElementById('mistStreamPushDestinationForm').showModal()
}

const editDestination = async (destination) => {
  mistStreamPushDestinationFormModalMode.value = 'edit'
  destinationDetails.value = destination
  document.getElementById('mistStreamPushDestinationForm').showModal()
  // console.log(`Editing destination with ID: ${destination}`)
  // const index = mistStreamPushDestinations.value.findIndex(destination => destination.id === destinationDetails.value.id)
  // if (index !== -1) {
  //   mistStreamPushDestinations.value[index].has_auto_push = 0
  // }
}

const destinationDetails = ref({})
const mistStreamPushDestinationFormModalMode = ref('add')

</script>