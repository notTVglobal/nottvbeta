<template>
  <div>
    <StreamPreviewOverlay />
  </div>
</template>

<script>
import NoLayout from '../../Layouts/NoLayout';
import { defineComponent } from 'vue'
import StreamPreviewOverlay from '@/Components/Pages/Stream/StreamPreviewOverlay.vue'

export default defineComponent({
  components: {StreamPreviewOverlay},
  layout: NoLayout,
})
</script>