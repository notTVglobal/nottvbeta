<template>
  <div class="fixed z-999 left-0 bottom-24">
    <div @click="openModal"
         class="h-14 w-3 bg-orange-500 text-white hover:bg-blue-500 hover:text-blue-500 hover:cursor-pointer">


      <div class="fixed z-50 left-3 bottom-24 w-28 h-14 bg-orange-950 hover:bg-blue-950 bg-opacity-80">
        <div class="pl-2 uppercase font-semibold text-sm tracking-wider ">
          Feedback
        </div>
        <div class="pl-2 text-xs tracking-wider w-36 pr-6">
          Found a bug? Have a question?
        </div>
      </div>
    </div>

  </div>


  <!-- Open the modal using ID.showModal() method -->
  <dialog id="feedbackConfirmationMessage" class="modal">
    <div class="modal-box bg-gray-800 border-2 border-accent">
      <div class="font-bold text-lg text-center mt-10 mb-6">
        <h5 class="uppercase tracking-wider">Thank you</h5>
        <h3>{{ $page.props.flash.feedback }}</h3>
      </div>


      <p class="py-4">Press ESC key or click outside to close</p>
    </div>
    <form method="dialog" class="modal-backdrop">
      <button>close</button>
    </form>
  </dialog>


  <dialog id="orangeFeedbackModal" class="modal text-black">

    <div v-if="!sentMessage">
      <div class="modal-box">
        <div class="flex flex-col text-center justify-center w-full">
          <div class="tracking-wider uppercase">
            Feedback Form
          </div>
        </div>

        <h3 class="font-bold text-lg">Hello!</h3>
        <p class="py-4">Thank you for being one of the first to test out notTV!</p>
        <p class="pb-4">Please use this form to send Cathy and Travis a message. If you have any problems with the website
          please include a description and a screenshot if you can.</p>
        <div>
          <textarea class="rounded-lg w-full" placeholder="message..." tabindex="0" v-model="form.message"></textarea>
          <input hidden type="file" @change="handleFileChange" accept="image/*">
        </div>
        <div class="modal-action w-full">
          <div class="flex w-full justify-between">
            <div class="my-auto font-semibold">
              Your feedback is really important!
            </div>
            <div class="space-x-2">
              <div @click.prevent="submit" class="btn btn-success text-white px-6" tabindex="0">Send</div>
              <div @click="closeModal" class="btn btn-warning" tabindex="0">Cancel</div>
            </div>
          </div>
        </div>

      </div>


    </div>


  </dialog>
</template>

<script setup>
import { useNotificationStore } from '@/Stores/NotificationStore'
import { useForm } from '@inertiajs/inertia-vue3'
import { usePage } from '@inertiajs/inertia-vue3'
import { computed, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'

const notificationStore = useNotificationStore()

const page = usePage()
const user = computed(() => page.props.value.user)

const screenshot = ref(null);

let form = useForm({
  message: '',
  // name: user.value.name,
  // email: user.value.email,
  // phone: user.value.phone,
  // city: user.value.city,
  // province: user.value.province,
})

function handleFileChange(event) {
  screenshot.value = event.target.files[0];
}

// const submit = () => {
//   let formData = new FormData();
//   // Append other form data
//   formData.append('screenshot', form.value.screenshot);
//   Inertia.post('/user/feedback', form)
//   form.message = ''
//   closeModal()
//   document.getElementById('feedbackConfirmationMessage').showModal()
// }



const submit = () => {
  const formData = new FormData();
  // Append other form data
  // Append text fields from the form
  Object.keys(form.data()).forEach(key => {
    formData.append(key, form.data()[key]);
  });

  // Append the file if it exists
  if (screenshot) {
    formData.append('screenshot', screenshot);
  }

  // Use Inertia's post method directly with formData
  // Notice: We're bypassing useForm's submit in this case because we need to send FormData
  form.post('/user/feedback', {
    // Specify that this request should be treated as FormData
    forceFormData: true,

    // Manually pass the FormData
    data: formData,

    // Optionally, handle onSuccess, onError, etc.
    onSuccess: () => {
      console.log('Form submitted successfully');
      form.message = ''
      closeModal()
      document.getElementById('feedbackConfirmationMessage').showModal()
    },
    onError: () => {
      console.log('Error submitting the form');
      form.message = ''
      closeModal()
      document.getElementById('feedbackConfirmationMessage').showModal()
    }
  });




  // formData.append('screenshot', form.value.screenshot);
  // Inertia.post('/user/feedback', form)
  // form.message = ''
  // closeModal()
  // document.getElementById('feedbackConfirmationMessage').showModal()
}

const openModal = () => {
  document.getElementById('orangeFeedbackModal').showModal()
  console.log(user.value.email)
}

const closeModal = () => {
  document.getElementById('orangeFeedbackModal').close()
  form.message = ''
}

</script>