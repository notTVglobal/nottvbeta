<template>
  <div class="relative flex flex-col items-center justify-center p-3">
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
