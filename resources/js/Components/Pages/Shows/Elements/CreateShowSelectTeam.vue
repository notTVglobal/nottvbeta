<template>
  <div class="mb-6">
    <label class="block mb-2 uppercase font-bold text-xs dark:text-gray-200"
           for="team"
    >
      Team <span :class="errors.team_id ? 'text-red-500' : 'text-indigo-500'">* REQUIRED</span>
    </label>
    <select
        class="border border-gray-400 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs text-gray-800"
        v-model="teamStore.id"
        required
    >
      <option disabled value="">Select Team</option>
      <option
          v-for="team in props.teams"
          :key="team.id"
          :value="team.id"
          class="bg-white text-black border-b dark:text-gray-50 dark:bg-gray-800 dark:border-gray-600"
          :class="'status-' + team.status.id"
      >
        {{ team.name }} ({{ team.status.status }})
      </option>

    </select>


    <div v-if="errors.team_id" v-text="errors.team_id" class="text-xs text-red-600 mt-1"></div>
  </div>
</template>

<script setup>
import { useTeamStore } from '@/Stores/TeamStore'
import { ref, watch } from 'vue'

const teamStore = useTeamStore()

const props = defineProps({
  teams: Object,
  errors: Object,
})

// Reactive property for the selected team ID
const selectedTeamId = ref(null)

// Watcher to update the teamStore.id when selectedTeamId changes
watch(selectedTeamId, (newId) => {
  teamStore.id = newId
  showStore.selectedTeamId = newId
})
</script>