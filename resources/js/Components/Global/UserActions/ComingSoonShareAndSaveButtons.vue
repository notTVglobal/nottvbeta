<template>
  <div class="flex flex-col items-center justify-center p-3 border-2 border-green-500">
    <div class="text-green-500 mb-2">Coming Soon!</div>
    <div class="flex flex-row gap-3">
      <SaveForLaterButton/>
    </div>
  </div>
</template>

<script setup>
import ShareButton from '@/Components/Global/UserActions/ShareButton.vue'
import SaveForLaterButton from '@/Components/Global/UserActions/SaveForLaterButton.vue'
</script>

<style scoped>
/* CSS specific to this button */
</style>
