<template>
  <!--  <div class="source-selector" :class="{'is-priority': source?.isPriority, 'is-playing': source?.isPlaying, 'is-fallback': source?.isFallback, 'has-error': source?.hasError}">-->
  <div
      :class="['source-selector', {'is-priority': source?.playback_priority_type, 'is-playing': source?.isPlaying, 'is-fallback': source?.isFallback, 'has-error': source?.hasError}]">
    <div>
      <!-- Display the source status here (playing, fallback, error) -->
      <div v-if="source?.playback_priority_type === sourceType" class="btn btn-xs mt-1 text-white bg-green-500 hover:bg-green-500 border-none">Priority</div>
      <span>

      </span>
      <!-- Icon or button to set this source as priority -->
      <!--      <button class="btn btn-xs" v-if="!source?.playback_priority_type" @click="setPriority">Set as Priority</button>-->
    </div>
  </div>

  <!--  <div v-if="source?.playback_priority_type === 'mistStream'">-->
  <!--    <button class="btn btn-xs hover:bg-green-500 bg-green-500">Priority</button>-->
  <!--  </div>-->

  <button v-if="source?.playback_priority_type !== sourceType" class="btn btn-xs mt-1 bg-gray-100 text-gray-800 hover:bg-gray-200 border-none"
          @click="setPriority">Set as Priority
  </button>


  <dialog :id="`confirmPriorityChangeModal`" class="modal">
    <div class="modal-box bg-white text-black">
      <h3 class="font-bold text-lg">Confirm Selection for {{ adminStore.sourceSelector?.source?.name }}</h3>
      <p class="py-4">
        Are you sure you want to change priority to <span class="font-bold">{{ adminStore.sourceSelector?.sourceType }}</span>?
      </p>
      <div class="modal-action">
        <button class="btn" @click.prevent="confirmPriorityChange">Confirm</button>
        <button class="btn" @click.prevent="closeModal">Cancel</button>
      </div>
    </div>
  </dialog>


</template>

<script setup>
import { useAdminStore } from '@/Stores/AdminStore'
import { ref } from 'vue'

const adminStore = useAdminStore()

let props = defineProps([
  'source',
  'sourceType',
])

// const setPriority = (sourceType) => {
//   emit('change', { sourceType, priority: true });
//   // Implement function to make API call to update the backend
// };


const setPriority = () => {
adminStore.sourceSelector.source = props.source
adminStore.sourceSelector.sourceType = props.sourceType
  document.getElementById('confirmPriorityChangeModal').showModal() // Open the modal for confirmation
}

const confirmPriorityChange = async () => {
  await adminStore.setPlaybackPriorityType(adminStore.sourceSelector.source, adminStore.sourceSelector.sourceType)
  document.getElementById('confirmPriorityChangeModal').close() // Close the modal
}

const closeModal = () => {
  document.getElementById('confirmPriorityChangeModal').close()
}

// const setPriority = async () => {
//   // Directly call the store action to update the priority.
//   await adminStore.setPlaybackPriorityType(props.source, props.sourceType);
//   // No need to emit an event to the parent component.
// };

</script>

<style scoped>
.source-selector {
  /* Base styles */
  /*margin-top: 0.5rem;*/
}

.is-playing {
  /* Styles for playing status */
}

.is-priority {
  /* Styles for isPriority status */
  color: black;
  font-weight: 300;
}

.is-fallback {
  /* Styles for fallback status */
}

.has-error {
  /* Styles for error status */
}

</style>
