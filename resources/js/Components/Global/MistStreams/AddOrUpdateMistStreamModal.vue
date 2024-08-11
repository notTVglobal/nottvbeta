<template>

  <dialog :id="id" class="modal">
    <div class="modal-box w-11/12 max-w-5xl bg-white text-black">
      <h3 class="font-bold text-lg">
        <slot name="form-title">Default Form Title</slot>
      </h3>


      <div>
        <slot name="form-description">Default Form Description</slot>
      </div>
      <div>
        <div class="flex flex-col space-y-2 px-12 mt-6">
          <form @submit.prevent="submit">

            <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                   for="name">Stream Name</label>
            <input v-model="form.name"
                   class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block"
                   type="text"
                   name="name"
                   id="name" placeholder="Stream Name...">
            <div v-if="form.errors.name" v-text="form.errors.name" class="text-xs text-red-600 mt-1"></div>

            <label class="block mt-4 mb-2 uppercase font-bold text-xs dark:text-gray-200"
                   for="name">Source</label>
            <input v-model="form.source"
                   class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block"
                   type="text"
                   name="source"
                   id="source" placeholder="Source...">
            <div v-if="form.errors.source" v-text="form.errors.source" class="text-xs text-red-600 mt-1"></div>

            <label class="block mt-4 mb-2 uppercase font-bold text-xs dark:text-gray-200"
                   for="name">MIME Type</label>
            <input v-model="form.mime_type"
                   class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block"
                   type="text"
                   name="mime_type"
                   id="mime_type" placeholder="MIME Type...">
            <div v-if="form.errors.mime_type" v-text="form.errors.mime_type" class="text-xs text-red-600 mt-1"></div>

            <label class="block mt-4 mb-2 uppercase font-bold text-xs dark:text-gray-200"
                   for="comment">Comment</label>
            <input v-model="form.comment"
                   class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block"
                   type="text"
                   name="comment"
                   id="comment" placeholder="Comment..."></input>
            <div v-if="form.errors.comment" v-text="form.errors.comment" class="text-xs text-red-600 mt-1"></div>

            <label class="block mt-4 mb-2 uppercase font-bold text-xs dark:text-gray-200"
                   for="metadata">Optional Parameters</label>
            <div v-for="(item, index) in form.metadata" :key="index" class="flex items-center">
              <input v-model="item.key"
                     class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 mt-2 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                     placeholder="Key">

              <input v-model="item.value"
                     class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 ml-2 mt-2 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                     placeholder="Value">

              <button @click.prevent="removeMetadataField(index)" class="btn btn-sm ml-2 mt-2">Remove</button>
            </div>
            <button @click.prevent="addMetadataField" class="btn btn-sm mt-2">Add Metadata</button>


            <div class="flex flex-row justify-center pt-6">
              <button type="submit" class="btn btn-wide bg-green-500 hover:bg-green-400 text-white"
                      :disabled="form.processing">
                <slot name="button-label">Default Form Title</slot>
              </button>

            </div>
          </form>
        </div>

      </div>


      <div class="modal-action">
        <button class="btn" @click.prevent="closeModal">Cancel</button>
        <form method="dialog">
          <!-- if there is a button, it will close the modal -->

        </form>
      </div>
    </div>
  </dialog>

</template>
<script setup>
import { onMounted, reactive, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useChannelStore } from '@/Stores/ChannelStore'
import { useAdminStore } from '@/Stores/AdminStore'
import { useNotificationStore } from '@/Stores/NotificationStore'

const channelStore = useChannelStore()
const adminStore = useAdminStore()
const notificationStore = useNotificationStore()

import Label from '@/Jetstream/Label.vue'
import Button from '@/Jetstream/Button.vue'

let props = defineProps({
  mistStream: Object,
  id: String,
  // errors: Object,
})

// const errors = ref(props.errors);
const modalVisible = ref(false)

const metadata = ref([{key: '', value: ''}])
let originalName = ref('') // used for stream name changes

const addMetadataField = () => {
  form.metadata.push({key: '', value: ''})
}

const removeMetadataField = (index) => {
  form.metadata.splice(index, 1)
}

let form = reactive(useForm({
  id: '',
  name: '',
  source: 'push://',
  mime_type: 'application/vnd.apple.mpegurl',
  comment: '',
  metadata: [], // Initialize with one empty metadata entry
}))

// Watch for changes in the mistStream object in your Pinia store
// This only applies if updating the mistStream
watch(() => channelStore.mistStream, (newVal) => {
  if (newVal) {
    // Populate the form with mistStream data
    form.id = newVal.id || ''
    form.name = newVal.name || ''
    originalName.value = newVal.name || '' // Capture the original name
    form.source = newVal.source || 'push://'
    form.mime_type = newVal.mime_type || 'application/vnd.apple.mpegurl'
    form.comment = newVal.comment || ''

    // Convert metadata object to array format
    if (newVal.metadata && typeof newVal.metadata === 'object') {
      form.metadata = Object.entries(newVal.metadata).map(([key, value]) => ({
        key, value,
      }))
    } else {
      form.metadata = [{key: '', value: ''}] // Reset to initial state if no metadata
    }
  } else {
    // Reset the form to initial state if there's no mistStream selected (e.g., for adding a new stream)
    form.reset()
  }
}, {deep: true, immediate: true})
//
// let submit = () => {
//   form.post(route('mistStream.add'))
//   form.name = ''
//   form.comment = ''
//   if (!form.errors) {
//     document.getElementById('adminAddMistStreamModal').close()
//   }
// }

const validateStreamName = (name) => {

  // Check if the name is empty
  if (!name.trim()) {
    console.warn('Validation Failed: Stream name is empty.');
    notificationStore.setGeneralServiceNotification('Invalid Stream Name', 'The stream name cannot be empty.');
    return { valid: false, reformattedName: null };
  }

  // Check if the original name exceeds 100 characters
  if (name.length > 100) {
    console.warn('Validation Failed: Original stream name exceeds 100 characters.');
    notificationStore.setGeneralServiceNotification('Invalid Stream Name', 'The stream name cannot exceed 100 characters.');
    return { valid: false, reformattedName: null };
  }

  let reformattedName = name.toLowerCase().replace(/[^a-z0-9._-]/g, '');
  console.log('Reformatted Stream Name:', reformattedName);

  // Check if the name starts with a number
  if (/^\d/.test(reformattedName)) {
    console.warn('Validation Failed: Stream name starts with a number.');
    notificationStore.setGeneralServiceNotification('Invalid Stream Name', 'The stream name cannot start with a number.');
    return { valid: false, reformattedName: null };
  }

  // Check if the reformatted name exceeds 100 characters
  if (reformattedName.length > 100) {
    console.warn('Validation Failed: Reformatted stream name exceeds 100 characters.');
    notificationStore.setGeneralServiceNotification('Invalid Stream Name', 'The stream name cannot exceed 100 characters.');
    return { valid: false, reformattedName: null };
  }


  // Check if the name was reformatted
  if (reformattedName !== name) {
    console.warn('Validation Warning: Stream name was reformatted.');
    form.name = reformattedName
    return { valid: false, reformattedName };
  }

  console.log('Validation Passed: Stream name is valid.');
  return { valid: true, reformattedName };
};


let submit = () => {
  console.log('Starting Stream Name Validation...');
  const { valid, reformattedName } = validateStreamName(form.name);

  if (!valid) {
    console.log('Stream Name Validation Failed.');
    if (reformattedName) {
      console.log('Showing notification for reformatted stream name.');
      notificationStore.setGeneralServiceNotification(
          'Stream Name Reformatted',
          `The stream name has been reformatted to "${reformattedName}". Please confirm before proceeding.`
      );
      // Optionally set the originalName to the reformatted one, but do not submit yet
      // originalName.value = reformattedName;
    }
    // Do not close the modal or submit
    console.log('Form submission aborted due to validation failure.');
    return;
  }

  console.log('Stream Name Validation Passed. Proceeding with submission.');

  if (originalName.value === '') {
    originalName.value = form.name
  }

  form.post(route('mistStream.addOrUpdate', { originalName: originalName.value }), {
    onSuccess: () => {
      console.log('Form submission successful.');
      postSubmissionActions(); // Call the async function
      closeModal(); // Close the modal only if the submission is successful
    },
    onError: (error) => {
      console.error('Error during submission:', error);

      // Display the error notification
      notificationStore.setToastNotification('An error occurred during submission. Please try again.', 'error');
      // Do not close the modal on error
      console.log('Form submission failed. Modal remains open.');
    },
  });
};

const postSubmissionActions = async () => {
  await adminStore.fetchItems('mistStream') // Await the fetching of items
  channelStore.clearMistStream() // Clear selected mistStream
  form.reset() // Reset form fields
  originalName.value = ''
  closeModal() // Close modal
}

function closeModal() {
  channelStore.clearMistStream()
  // Reset the form fields to their initial values
  form.reset()
  // Clear all validation errors
  form.clearErrors()
  document.getElementById(props.id).close()
}

// function toggleModal() {
//   console.log('Toggling modal:', !modalVisible.value)
//   modalVisible.value = !modalVisible.value
//   form.name = ''
//   form.comment = ''
// }
</script>
