<template>
  <dialog id="changeShowScheduleModal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl">

      <div class="flex flex-row justify-center text-center">
        <div>
          <h2 class="font-bold text-xl">
            Change Show Schedule
          </h2>
          <div class="my-6 font-medium">
            We are developing this feature. Check back soon.
            <!--            <slot name="form-description">Default Form Description</slot>-->
          </div>
          <div class="mb-6">
            For now you can <button @click.prevent="removeFromSchedule" class="text-blue-700 hover:text-blue-500">remove the show</button> from the schedule and re-add it again.
          </div>
          <div v-if="errors" class="mb-6 text-red-700">
            <div>{{ errors }}</div>
            <div>Please let Travis know.</div>
          </div>
          <div class="flex flex-row justify-center">
            <button @click.prevent="closeModal" class="btn">Okay</button>
          </div>
        </div>
      </div>
    </div>
  </dialog>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia';
import { ref } from 'vue'

let props = defineProps({
  show: Object
})

const errors = ref()

const removeFromSchedule = async() => {
  const payload = {
    data: {
      contentType: props.show.scheduleDetails[0].contentType,
      contentId: props.show.scheduleDetails[0].contentId,
    }
  }
  await axios.delete('/api/schedule/removeFromSchedule', payload)
      .then(() => {
        // Assuming `show` is accessible here, otherwise, you might need `props.show.slug`
        Inertia.visit(`/shows/${props.show.slug}/manage`);
      })
      .catch(error => {
        console.error('There was an error removing the item from the schedule:', error);
        errors.value = error
        // Handle error (show a message, etc.)
      });
}

function closeModal() {
  document.getElementById('changeShowScheduleModal').close()
}
</script>