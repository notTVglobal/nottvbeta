<template>
  <div class="p-3 border-2 border-green-500">
    <div class="text-green-500 mb-2">Coming Soon! (These buttons are currently being built)</div>
    <div class="flex flex-row gap-3">
      <SaveForLaterButton/>
      <ShareButton/>
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
