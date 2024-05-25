<template>
  <div class="min-h-80">
    <SearchShowEpisodesComponent :modelType="'team'" :modelId="team.id" :modelSlug="team.slug"
                                 :shows="shows">
      <template v-slot:title>
        <h2 class="text-white text-lg font-semibold mb-2">Advanced Episode Search</h2>
      </template>
      <template v-slot:description>
        <p class="text-gray-400 text-sm mb-4">Search through all of our shows to find episodes.</p>
      </template>
    </SearchShowEpisodesComponent>
  </div>
</template>
<script setup>
import { useTeamStore } from '@/Stores/TeamStore'
import SearchShowEpisodesComponent from '@/Components/Global/Search/SearchShowEpisodesComponent.vue'
import { computed } from 'vue'

const teamStore = useTeamStore()

// Map store state to local computed properties
const team = computed(() => teamStore.team || {});
const shows = computed(() => teamStore.shows || {});

</script>