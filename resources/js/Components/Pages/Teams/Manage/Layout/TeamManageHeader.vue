<template>
  <div>
    <div class="flex justify-between mb-8 pt-6">
      <div class="inline-flex items-center text-3xl font-semibold relative">

        <SingleImage :image="team.image" :alt="'team logo'" :class="'w-20 mr-2'"/>

        <Link :href="`/teams/${team.slug}`" class="uppercase text-black">{{ team.name }}</Link>
        <div
            class="bg-green-400 w-5 h-5 text-xs text-white rounded-full flex justify-center items-center absolute -left-3 -top-0.5">
          {{ teamStore.membersCountDisplay }}
        </div>
      </div>

      <div v-if="can.hasSpecialPermission">

        <EditPublicMessageModal />
        <ChangeNextBroadcastDetails />

      </div>

    </div>
    <div class="flex justify-between text-black">

      <div><span class="text-xs font-semibold mr-2 uppercase">Team Leader: </span>
        <span class="font-bold" v-if="team?.teamLeader?.name">{{ team?.teamLeader?.name }}</span>
        <span v-else>No team leader assigned</span>
      </div>
      <div class="w-1/2 lg:w-1/3">
        <div class="uppercase text-sm">Public Message:</div>
        <span v-html="team?.public_message"/>
      </div>
    </div>

  </div>


</template>

<script setup>
import { computed, watchEffect } from 'vue'
import { useTeamStore } from '@/Stores/TeamStore'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'
import EditPublicMessageModal from '@/Components/Pages/Teams/Manage/Elements/EditPublicMessageModal.vue'
import ChangeNextBroadcastDetails from '@/Components/Pages/Teams/Manage/Elements/ChangeNextBroadcastDetails.vue'

const teamStore = useTeamStore()

// Map store state to local computed properties
const team = computed(() => teamStore.team || {});
const can = computed(() => teamStore.can);

</script>
