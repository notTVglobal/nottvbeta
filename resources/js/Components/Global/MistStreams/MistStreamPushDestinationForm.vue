<template>
  <!-- You can open the modal using ID.showModal() method -->

  <dialog id="mistStreamPushDestinationForm" class="modal">
    <div class="modal-box bg-white dark:bg-gray-800 dark:text-white">
      <form method="dialog">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
      </form>
      <h3 class="font-bold text-lg">{{ mode === 'add' ? 'Add Push Destination' : 'Edit Push Destination' }}</h3>

      <form @submit.prevent="submitForm">
        <!-- Form fields for your model's attributes -->
        <div class="my-3 flex flex-col space-y-2">
          <input v-model="form.rtmp_url"
                 type="text"
                 placeholder="RTMP URL"
                 class="input input-bordered bg-white dark:bg-gray-800 dark:text-white"
                 required >
          <input v-model="form.rtmp_key"
                 type="text"
                 placeholder="RTMP Key"
                 class="input input-bordered bg-white dark:bg-gray-800 dark:text-white" >
          <textarea v-model="form.comment" class="textarea textarea-bordered bg-white dark:bg-gray-800 dark:text-white" placeholder="Optional Comment..." />

          <div v-if="notificationStore.errorMessage" role="alert" class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div class="w-full flex flex-row justify-between">
              <div>{{ notificationStore.formattedErrorMessage }}</div>
              <div><button @click.prevent="notificationStore.clearErrorMessage" class="btn btn-xs">OK</button></div>
            </div>
          </div>
          <!-- Add other fields as necessary -->
          <button type="submit"
                  :disabled="goLiveStore.pushDestinationFormSubmitProcessing"
                  class="mt-2 btn btn-primary text-white disabled:cursor-not-allowed disabled:bg-gray-400">
            <span v-if="!goLiveStore.pushDestinationFormSubmitProcessing">{{ mode    === 'add' ? 'Add' : 'Save Changes' }}</span>
            <span v-else>Saving<span class="my-2 loading loading-dots loading-sm"></span> Please Wait</span>
          </button>
        </div>
      </form>
    </div>
  </dialog>
</template>

<script setup>
import { ref, watchEffect } from 'vue'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import { useMistStore } from '@/Stores/MistStore'

const notificationStore = useNotificationStore()
const goLiveStore = useGoLiveStore()
const mistStore = useMistStore()

let props = defineProps({
  destinationDetails: Object,
  mode: String,
})

// Define your emits
const emit = defineEmits(['update-success']);

const form = ref({
  show_id: goLiveStore.selectedShowId,
  stream_name: goLiveStore.streamKey,
  mist_stream_wildcard_id: props.destinationDetails.mist_stream_wildcard_id,
  id: props.destinationDetails.id,
  rtmp_url: props.destinationDetails.rtmp_url,
  rtmp_key: props.destinationDetails.rtmp_key,
  comment: props.destinationDetails.comment,
  errors: '',
  // Initialize other model attributes here
});

// Use watchEffect to reactively update form whenever props.destinationDetails changes
watchEffect(() => {
  form.value.mist_stream_wildcard_id = props.destinationDetails.mist_stream_wildcard_id || '';
  form.value.id = props.destinationDetails.id || '';
  form.value.rtmp_url = props.destinationDetails.rtmp_url || '';
  form.value.rtmp_key = props.destinationDetails.rtmp_key || '';
  form.value.comment = props.destinationDetails.comment || '';
  // Initialize other model attributes here
});

const submitForm = async () => {
  goLiveStore.pushDestinationFormSubmitProcessing = true
  let url = '/mist-stream-push-destinations'; // Default URL for adding
  let method = 'post'; // Default method for adding

  // Check if the mode is 'edit' to adjust the URL and method as needed
  if (props.mode === 'edit' && props.destinationDetails.id) {
    url += `/${props.destinationDetails.id}`; // Append the destination ID for the update operation
    method = 'put'; // Use PUT for updating, assuming your Laravel resource controller follows RESTful conventions
  }

  try {
    // Perform the request using Axios
    const response = await axios({
      method: method,
      url: url,
      data: form.value, // Assuming formData is a reactive ref holding your form data
    });

    // Handle response here, e.g., show a success message, fetch updated list, etc.
    // console.log('Success:', response.data);
    emit('update-success');
    form.value.errors = ''

    // Assuming the wildcard ID is part of the response or known at this point
    // const wildcardId = response.data.wildcardId || someOtherWayToDetermineWildcardId();
    // console.log('just about to send this wildcardID: ' + goLiveStore.wildcardId)
    // await mistStore.getMistStreamPushDestinations(goLiveStore.wildcardId); // Fetch updated push destinations
    // Close the modal
    await goLiveStore.fetchPushDestinations(); // Fetch updated push destinations
    goLiveStore.pushDestinationFormSubmitProcessing = false
    document.getElementById('mistStreamPushDestinationForm').close();

  } catch (error) {
    if (error.response && error.response.data && error.response.data.errors) {
      // Assign all errors from the response to formErrors
      notificationStore.errorMessage = error.response.data.errors;
      goLiveStore.pushDestinationFormSubmitProcessing = false
    } else {
      // General error handling
      // console.error('Submission error', error);
      notificationStore.setGeneralServiceNotification('Submission error', error)
      goLiveStore.pushDestinationFormSubmitProcessing = false
    }
  }
}

</script>