<template>
  <button class="btn btn-sm bg-green-500 hover:bg-green-600 text-white px-4 py-2 mr-2 rounded-lg disabled:bg-gray-400"
          onclick="adminAddMistStreamModal.showModal()">
    Add Mist Stream
  </button>
  <dialog id="adminAddMistStreamModal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl">
      <h3 class="font-bold text-lg">Add Mist Stream</h3>

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
                   for="comment">Comment</label>
            <textarea v-model="form.comment"
                      class="bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block"
                      type="text"
                      name="comment"
                      id="comment" placeholder="Comment..."></textarea>
            <div v-if="form.errors.comment" v-text="form.errors.comment" class="text-xs text-red-600 mt-1"></div>
            <div class="flex flex-row justify-center pt-6">
              <button type="submit" class="btn btn-wide bg-green-500 hover:bg-green-400 text-white"
                      :disabled="form.processing">Add
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
import { ref } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'

import Label from '@/Jetstream/Label.vue'
import Button from '@/Jetstream/Button.vue'

let props = defineProps({
  mistStream: Object,
  // errors: Object,
})

// const errors = ref(props.errors);
const modalVisible = ref(false)

let form = useForm({
  name: '',
  comment: '',
})
//
// let submit = () => {
//   form.post(route('mistStream.add'))
//   form.name = ''
//   form.comment = ''
//   if (!form.errors) {
//     document.getElementById('adminAddMistStreamModal').close()
//   }
// }

let submit = () => {
  form.post(route('mistStream.add'), {
    onSuccess: () => {
      // Clear the form fields only if submission is successful
      form.reset(); // Resets the form fields to their initial values

      // Close the modal if there are no errors
      // This is in onSuccess, so it only runs if the submission was successful
      document.getElementById('adminAddMistStreamModal').close();
    },
    onError: () => {
      // Handle errors if needed, e.g., log to console or show a message
      // Errors are automatically attached to the form object
    }
  });
}

function closeModal() {
  document.getElementById('adminAddMistStreamModal').close()
  // Reset the form fields to their initial values
  form.reset();
  // Clear all validation errors
  form.clearErrors();
}

// function toggleModal() {
//   console.log('Toggling modal:', !modalVisible.value)
//   modalVisible.value = !modalVisible.value
//   form.name = ''
//   form.comment = ''
// }
</script>