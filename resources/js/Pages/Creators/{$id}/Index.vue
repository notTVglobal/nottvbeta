<template>
  <div>
    <div id="topDiv"></div>
    <ShowCreators :creator="creator"/>
  </div>
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup';
import ShowCreators from '@/Components/Pages/Creators/ShowCreators.vue'
import { defineProps, onMounted } from 'vue'

const props = defineProps({
  creator: Object,
});

usePageSetup('creator.' + props.creator.id);
onMounted(() => {
  // Only scroll into view if there are no query strings
  const topDiv = document.getElementById('topDiv')
  if (topDiv) {
    topDiv.scrollIntoView()
  }
})
</script>
