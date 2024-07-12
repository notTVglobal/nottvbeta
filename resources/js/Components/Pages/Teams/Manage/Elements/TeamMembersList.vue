<template>
  <div v-if="can.manageTeam">
    <button
        class="bg-green-500 hover:bg-green-600 text-white font-semibold ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
        @click="openModal"
        :disabled="!teamStore.spotsRemaining"
    >Add Member ({{ teamStore.spotsRemaining }} spots left)
    </button>
  </div>
  <div class="overflow-x-auto">
    <table class="table min-w-full">
      <thead>
      <tr>
        <td class="px-6 min-w-[3rem] max-w-[3rem] whitespace-nowrap text-sm font-medium">
          <!-- Avatar -->
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <div class="flex items-center">

            <div class="pl-3 text-sm font-medium">
              Name
            </div>

          </div>
        </td>

        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
          Position
        </td>

        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
          Phone
        </td>

        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
          Email
        </td>

        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
          Status
        </td>

        <td v-if="can.manageTeam" class="px-6 py-4 whitespace-nowrap text-sm font-medium">
          <!-- Remove -->
        </td>
      </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
      <TeamMember v-for="member in team.members" :member="member" :team="team" :key="member.id" :can="can"/>
      </tbody>
    </table>
  </div>
  <div class="text-right px-3 mt-2 text-gray-600 italic w-full"
       v-show="!teamStore.spotsRemaining">
    There are no remaining team spots. Edit the team to add more.
  </div>

  <Teleport to="body">
    <TeamAddMember />
  </Teleport>


</template>

<script setup>
import { computed, onBeforeMount } from 'vue'
import { useTeamStore } from "@/Stores/TeamStore"
import TeamMember from "@/Components/Pages/Teams/Manage/Elements/TeamMember"
import TeamAddMember from "@/Components/Pages/Teams/Manage/Elements/TeamAddMember"

const teamStore = useTeamStore()

// Map store state to local computed properties
const team = computed(() => teamStore.team || {});
const can = computed(() => teamStore.can || {});

// let props = defineProps({
//   creators: Object,
//   creatorFilters: Object,
// })

onBeforeMount(async () => {
  teamStore.showModal = false;
})

function openModal() {
  teamStore.showModal = true;
}

</script>
