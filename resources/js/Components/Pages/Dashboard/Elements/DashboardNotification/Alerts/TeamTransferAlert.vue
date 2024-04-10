<template>
  <div class="py-3">
    <div class="flex flex-wrap justify-center mb-4">
      <span class="text-lg font-bold uppercase text-blue-200">Notification</span>
    </div>
    <div class="text-center">
      <p class="mb-4"><span class="text-xl font-bold">You have received a Team Transfer! </span><br>
        <span>Someone has offered to transfer you their team.</span></p>
      <div class="space-x-4">
        <button
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            @click="acceptTransfer">Accept
        </button>
        <button
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            @click="rejectTransfer">Reject
        </button>
        <button
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            @click="moreInfo">More Info
        </button>
      </div>
    </div>
    <p class="alert alert-info my-3 ">Feature in development. This is for demonstration purposes only.</p>
  </div>
</template>
<script>
// import { ref, computed } from 'vue';
//
// const acceptTransfer = () => {
//     // Handle accept transfer
// };
//
// const rejectTransfer = () => {
//     // Handle reject transfer
// };
//
// const moreInfo = () => {
//     // Handle more info action, possibly open a modal
// };
</script>

<style scoped>
/* Additional styling if needed */
</style>
