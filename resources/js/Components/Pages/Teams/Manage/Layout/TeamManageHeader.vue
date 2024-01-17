<template>


  <div>
    <div class="flex justify-between mb-8 pt-6">
      <div class="inline-flex items-center text-3xl font-semibold relative">

        <SingleImage :image="image" :alt="'team logo'" :class="'w-20 mr-2'"/>

        <Link :href="`/teams/${team.slug}`" class="uppercase text-black">{{ team.name }}</Link>
        <div
            class="bg-green-400 w-5 h-5 text-xs text-white rounded-full flex justify-center items-center absolute -right-4 -top-0.5">
          {{ team.members.length }}
        </div>
      </div>
    </div>
    <div class="flex justify-between text-black">

      <div><span class="text-xs font-semibold mr-2 uppercase">Team Leader: </span>
        <span class="font-bold" v-if="teamLeader.name">{{ teamLeader.name }}</span>
        <span v-else>No team leader assigned</span>
      </div>
    </div>

    <div class="flex justify-end">
      <button
          v-if="can.editTeam"
          @click="appSettingStore.btnRedirect(`/teams/${team.slug}/edit`)"
          class="bg-blue-500 hover:bg-blue-600 text-white font-semibold ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
      >Edit
      </button>
    </div>


  </div>


</template>

<script setup>
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"

const appSettingStore = useAppSettingStore()

defineProps({
  team: Object,
  teamLeader: Object,
  logo: String,
  image: Object,
  can: Object,
})
</script>
