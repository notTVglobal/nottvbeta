<template>
  <div>

    <MyTeamsHeader :can="can"/>

    <div v-if="!teams.data && teams.data.length < 0" class="italic dark:text-gray-50 light:text-black">
      You have no teams.
    </div>
    <div v-else class="space-y-2 mb-56 lg:mb-28">
      <div
          v-for="team in teams.data"
          :key="team.id"
          class="border-b light:bg-white hover:bg-blue-300 dark:bg-gray-600 dark:border-gray-700 dark:hover:bg-blue-800 inset-x-0 bottom-0"
      >
        <button
            @click="visitTeamManagePage(team.slug)"
            class="py-4 w-full text-left transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 rounded-lg overflow-hidden shadow-lg bg-white dark:bg-gray-700 hover:bg-blue-100 dark:hover:bg-blue-900 text-blue-800 hover:text-blue-900 dark:text-blue-100 dark:hover:text-white"
        >
          <SingleImage :image="team.image" :alt="team.name" :class="`h-10 w-auto object-contain mx-auto`"/>
          <p class="px-4 py-2 text-center font-semibold uppercase">
            {{ team.name }}
          </p></button>
      </div>
    </div>

    <MyTeamsFooter :teams="teams"/>

  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import MyTeamsHeader from "@/Components/Pages/Dashboard/Elements/MyTeams/MyTeamsHeader.vue"
import MyTeamsFooter from "@/Components/Pages/Dashboard/Elements/MyTeams/MyTeamsFooter.vue"
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

defineProps({
  can: Object,
  teams: Object,
})

function visitTeamManagePage(teamSlug) {
  router.visit(`/teams/${teamSlug}/manage`)
}
</script>
