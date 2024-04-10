<template>
  <div :class="appSettingStore.ott !== 0 ? 'hidden lg:block' : ''" class="fixed z-999 mx-auto bottom-36">
    <div class="absolute w-12 h-full  z-999 hover:cursor-pointer"
         @mouseover="hover = true"
         @mouseleave="hover = false"
         @click="openModal"
         ></div>
    <div
         class="h-custom w-3 bg-orange-500 z-100 text-white">


<!--      <div class="fixed z-50 left-3 bottom-24 w-28 h-14 bg-orange-950 hover:bg-blue-950 bg-opacity-80">-->
<!--        <div class="pl-2 uppercase font-semibold text-sm tracking-wider ">-->
<!--          Feedback-->
<!--        </div>-->
<!--        <div class="pl-2 text-xs tracking-wider w-36 pr-6">-->
<!--          Found a bug? Have a question?-->
<!--        </div>-->
<!--      </div>-->

<!--      <div class="group fixed z-50 left-3 bottom-24 w-28 h-14 bg-orange-950 bg-opacity-0 hover:bg-opacity-80 bg-opacity-transition hover:bg-blue-950 flex flex-col justify-center hover:flex-row transition-all duration-300">-->
<!--        <div class="pl-2 uppercase font-semibold text-sm tracking-wider rotate-90 group-hover:rotate-0 transition-transform">-->
<!--          Feedback-->
<!--        </div>-->
<!--        <div class="pl-2 text-xs tracking-wider w-36 pr-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300">-->
<!--          Found a bug? Have a question?-->
<!--        </div>-->
<!--      </div>-->

      <div :class="[
    'fixed z-50 left-3 bottom-36 w-32 h-custom flex flex-col justify-center transition-all duration-300 bg-opacity-transition',
    'group',
    notificationStore.showOrangeFeedbackBox || hover ? 'bg-blue-950 bg-opacity-100 flex-row pb-5' : 'flex-col',
    ]"

      >
        <div :class="[
      'pl-2 uppercase font-semibold text-sm tracking-wider transition-transform',
      notificationStore.showOrangeFeedbackBox || hover ? 'rotate-0' : 'rotate-90',
    ]">
          <span
                :class="[notificationStore.showOrangeFeedbackBox || hover ? '' : 'bg-orange-950 bg-opacity-80 px-2 pb-1']">Feedback</span>
        </div>
        <div class="pl-2 text-xs tracking-wider w-36 pr-6 transition-opacity duration-300"
             :class="[notificationStore.showOrangeFeedbackBox || hover ? 'opacity-100' : 'opacity-0']"
        >
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

    <div>
      <div class="modal-box">
        <div class="flex flex-col text-center justify-center w-full">
          <div class="tracking-wider uppercase text-orange-500">
            Feedback Form
          </div>
        </div>

        <h3 class="font-bold text-lg">Hello!</h3>
        <p class="py-4">Thank you for being one of the first to test out notTV. Use this form to send Cathy and Travis a message. <br /><br />If you have any problems with the website
          please include a detailed description.</p>
        <div>
          <textarea class="rounded-lg w-full" placeholder="message..." tabindex="0" v-model="form.message"></textarea>
          <input hidden type="file" @change="handleFileChange" accept="image/*">
        </div>
        <div class="modal-action w-full">
          <div class="flex flex-row flex-wrap w-full justify-between">
            <div class="my-auto font-semibold">
              Your feedback is really important to us!
            </div>
            <div class="space-x-2 space-y-2">
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
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { useForm } from '@inertiajs/inertia-vue3'
import { usePage } from '@inertiajs/inertia-vue3'
import { computed, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'

const appSettingStore = useAppSettingStore()
const notificationStore = useNotificationStore()

const page = usePage()
const user = computed(() => page.props.value.user)

const screenshot = ref(null);
const hover = ref(false);

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
  notificationStore.showOrangeFeedbackBox = true
}

const closeModal = () => {
  document.getElementById('orangeFeedbackModal').close()
  form.message = ''
  notificationStore.showOrangeFeedbackBox = false
}

</script>

<style scoped>
.h-custom {
  height: 6rem; /* or whatever value you choose */
}
/* Custom CSS for rotating the feedback text */
.rotate-90 {
  transform: rotate(-90deg);
  transform-origin: left bottom;
  margin-top: 7.7rem;
  margin-left: 1.1rem;
}

/* Control the transition for a smooth effect */
.transition-transform {
  transition: transform 0.3s ease;
}

/* Additional styles for background opacity transition */
.bg-opacity-transition {
  transition: background-color 0.3s ease;
}
</style>