<template>
  <div class="source-selector" :class="{'is-priority': source?.isPriority, 'is-playing': source?.isPlaying, 'is-fallback': source?.isFallback, 'has-error': source?.hasError}">
    <div @click="setPriority('sourceType')">
      <!-- Display the source status here (playing, fallback, error) -->
      <span >
        {{ source?.name }}
      </span>
      <!-- Icon or button to set this source as priority -->
      <button class="btn btn-xs" v-if="!source?.isPriority" @click="setPriority(source?.type)">Set as Priority</button>
    </div>
  </div>
</template>

<script setup>
defineProps(['source', 'priority']);
defineEmits(['change']);

const setPriority = (sourceType) => {
  emit('change', { sourceType, priority: true });
  // Implement function to make API call to update the backend
};
</script>

<style scoped>
.source-selector {
  /* Base styles */
  margin-top: 0.5rem;
}
.is-playing {
  /* Styles for playing status */
}
.is-priority {
  /* Styles for isPriority status */
  background-color: #0a7350;
}
.is-fallback {
  /* Styles for fallback status */
}
.has-error {
  /* Styles for error status */
}

</style>
