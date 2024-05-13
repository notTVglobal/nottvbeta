<template>
  <div class="min-h-80">
    <ExpandableDescription :description="team.description"/>
  </div>
</template>
<script setup>
import ExpandableDescription from '@/Components/Global/Text/ExpandableDescription.vue'

const props = defineProps({
  team: Object
})
</script>