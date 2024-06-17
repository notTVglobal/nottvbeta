<template>
  <div class="min-h-80 mb-4">
    <ExpandableDescription :description="team.description"/>
  </div>
</template>
<script setup>
import { useTeamStore } from '@/Stores/TeamStore'
import ExpandableDescription from '@/Components/Global/Text/ExpandableDescription.vue'
import { computed } from 'vue'

const teamStore = useTeamStore()

// Map store state to local computed properties
const team = computed(() => teamStore.team || {});

</script>