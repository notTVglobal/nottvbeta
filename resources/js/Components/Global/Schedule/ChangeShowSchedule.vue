<template>
  <dialog id="changeScheduleModal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl text-black bg-white dark:bg-gray-800 dark:text-white">

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
          <div class="mt-4 mb-2 text-left"></div>
          <StepSixCongratulations/>
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
import { onMounted, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useShowStore } from '@/Stores/ShowStore'
import StepSixCongratulations from '@/Components/Global/Schedule/StepSixCongratulations.vue'

const showStore = useShowStore()

const page = usePage().props

let props = defineProps({
  show: Object
})

const errors = ref()

const closeModal = () => {
  document.getElementById('changeScheduleModal').close()
}

const removeFromSchedule = async () => {
  await showStore.removeFromSchedule('App\\Models\\Show', props.show.id)
  closeModal()
};


</script>
