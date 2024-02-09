<template>
  <button class="btn btn-sm bg-green-500 hover:bg-green-600 text-white px-4 py-2 mr-2 rounded-lg disabled:bg-gray-400"
          onclick="adminAddMistStreamModal.showModal()">
    Add Mist Stream
  </button>
  <dialog id="adminAddMistStreamModal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl">
      <h3 class="font-bold text-lg">Add Mist Stream</h3>

      <div>


      </div>








      <div class="modal-action">
        <form method="dialog">
          <!-- if there is a button, it will close the modal -->
          <button class="btn">Close</button>
        </form>
      </div>
    </div>
  </dialog>

</template>
<script setup>
import { ref } from 'vue'
defineProps({
  mistStream: Object,
})
const modalVisible = ref(false);

function toggleModal() {
  console.log('Toggling modal:', !modalVisible.value);
  modalVisible.value = !modalVisible.value;
}
</script>