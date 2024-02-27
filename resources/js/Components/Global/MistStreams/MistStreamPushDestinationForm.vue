<template>
  <!-- You can open the modal using ID.showModal() method -->

  <dialog id="mistStreamPushDestinationForm" class="modal">
    <div class="modal-box">
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
                 class="input input-bordered"
                 required >
          <input v-model="form.rtmp_key"
                 type="text"
                 placeholder="RTMP Key"
                 class="input input-bordered" >
          <textarea v-model="form.comment" class="textarea textarea-bordered" placeholder="Optional Comment..." />

          <div v-if="form.errors" class="text-red-700">{{ form.errors }}</div>
          <!-- Add other fields as necessary -->
          <button type="submit" class="mt-2 btn btn-primary text-white">{{ mode    === 'add' ? 'Add' : 'Save Changes' }}</button>
        </div>
      </form>
    </div>
  </dialog>
</template>

<script setup>
import { ref, watchEffect } from 'vue'

let props = defineProps({
  destinationDetails: Object,
  mode: String,
})

// Define your emits
const emit = defineEmits(['update-success']);

const form = ref({
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
    console.log('Success:', response.data);
    emit('update-success');
    form.value.errors = ''

    // Close the modal
    document.getElementById('mistStreamPushDestinationForm').close();
  } catch (error) {
    if (error.response && error.response.data && error.response.data.errors) {
      // Assign all errors from the response to formErrors
      form.value.errors = error.response.data.errors;
    } else {
      // General error handling
      console.error('Submission error', error);
    }
  }
}

</script>