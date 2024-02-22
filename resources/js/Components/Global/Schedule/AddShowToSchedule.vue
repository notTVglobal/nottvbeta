<template>

  <dialog :id="id" class="modal">
    <div class="modal-box w-11/12 max-w-5xl">
      <h3 class="font-bold text-lg">
        <slot name="form-title">Default Form Title</slot>
      </h3>


      <div>
        <slot name="form-description">Default Form Description</slot>
      </div>
      <div>
        <div class="flex flex-col space-y-2 px-12 mt-6">
          <form @submit.prevent="submit">

            <ul class="steps">
              <li class="step step-primary">Choose days of the week</li>
              <li class="step step-primary">Choose start time</li>
              <li class="step">Choose duration</li>
              <li class="step">Choose start date</li>
              <li class="step">Choose end date</li>
            </ul>

          <ol>
            <li>
              1. Choose days of the week. Checkboxes. Sunday, Monday, Tu.... Saturday
            </li>
            <li>
              2. Choose start time
            </li>
            <li>
              3. Choose duration
            </li>
            <li>
              4. Choose start date
            </li>
            <li>
              5. Choose end date (cannot be longer than 3 months, so 3 months is pre-set)
            </li>
          </ol>

            Last "page": Congratulations! You've scheduled your show on notTV!

            <ul>
              <li>
                * You will see a countdown to your next live on your Show Manage page and your Dashboard
              </li>
              <li>
                * You can change your schedule at any time. But you may lose your priority spot
              </li>
              <li>
                * Priority is first come first serve. If there are scheduling conflicts the creator's who pick a spot first get the priority until we build additional priority features.
              </li>
              <li>
                * Each creator is limited to 3 shows for now as we build our notTV MVP (minimum viable product)
              </li>
              <li>
                * Connect your live stream at least 5 minutes before your scheduled live. If you miss a scheduled time slot you lose your priority spot.
              </li>
              <li>
                * You can schedule an episode to playback in place of your live stream at your scheduled time if you won't be able to go live. This allows you to pre-record content to release so you don't lose your priority spot.
              </li>
            </ul>

            NEXT: Invite a Creator!

            Here's 10 invite codes for you fans/audience. They will each get 10 invite codes as well.

            <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
                   for="name">Stream Name</label>
            <input v-model="form.name"
                   class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block"
                   type="text"
                   name="name"
                   id="name" placeholder="Stream Name...">
            <div v-if="form.errors.name" v-text="form.errors.name" class="text-xs text-red-600 mt-1"></div>


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
import { reactive, ref, watch } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { useChannelStore } from '@/Stores/ChannelStore'
import { useAdminStore } from '@/Stores/AdminStore'

const channelStore = useChannelStore()
const adminStore = useAdminStore()

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
let originalName = ref(''); // used for stream name changes

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

form.reset() // on modal load, reset form.

// Watch for changes in the mistStream object in your Pinia store
watch(() => channelStore.mistStream, (newVal) => {
  if (newVal) {
    // Populate the form with mistStream data
    form.id = newVal.id || ''
    form.name = newVal.name || ''
    originalName.value = newVal.name || ''; // Capture the original name
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

let submit = async () => {
  await form.post(route('mistStream.addOrUpdate', { originalName: originalName.value })), {
    onSuccess: () => {
      postSubmissionActions(); // Call the async function
    },
    onError: () => {
      // Handle errors if needed, e.g., log to console or show a message
      // Errors are automatically attached to the form object
    },

  }
  closeModal()
}

const postSubmissionActions = async () => {
  await adminStore.fetchItems('mistStream'); // Await the fetching of items
  channelStore.clearMistStream(); // Clear selected mistStream
  form.reset(); // Reset form fields
  closeModal(); // Close modal
};

function closeModal() {
  document.getElementById(props.id).close()
  channelStore.clearMistStream()
  // Reset the form fields to their initial values
  form.reset()
  // Clear all validation errors
  form.clearErrors()
}

// function toggleModal() {
//   console.log('Toggling modal:', !modalVisible.value)
//   modalVisible.value = !modalVisible.value
//   form.name = ''
//   form.comment = ''
// }
</script>