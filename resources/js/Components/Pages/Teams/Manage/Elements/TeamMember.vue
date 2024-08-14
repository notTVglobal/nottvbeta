<template>
  <tr class="divide-y divide-gray-200">
    <td class="text-gray-500 px-6 py-4 min-w-[3rem] max-w-[3rem] text-sm">
      <img v-if="member.profile_photo_path"
           :src="`/storage/${member.profile_photo_path}`"
           alt=""
           class="skeleton min-h-[3rem] min-w-[3rem] max-h-[3rem] max-w-[3rem] rounded-full object-cover">
      <img v-if="!member.profile_photo_path"
           :src="`${member.profile_photo_url}`"
           alt=""
           class="skeleton min-h-[3rem] min-w-[3rem] max-h-[3rem] max-w-[3rem] rounded-full object-cover">
    </td>

    <td class="text-xl font-medium px-6 py-4">
      <div class="pl-3">
        {{ member.name }}
      </div>
    </td>

    <td class="light:text-gray-600 px-6 py-4 text-sm">
      <span v-if="member.id === teamStore.team?.teamLeader?.id" class="text-indigo-700">Team Leader</span>
      <span v-else-if="member.id === teamStore.team?.teamCreator?.id" class="text-indigo-700">Team Creator</span>
      <span v-else-if="teamStore.team.managers.some(manager => manager.id === member.id)" class="text-orange-700">Team Manager</span>
      <span v-else>Team Member</span>
    </td>

    <td class="light:text-gray-600 px-6 py-4 text-sm">
      {{ member.phone }}
    </td>

    <td class="light:text-gray-600 px-6 py-4 text-sm">
      {{ member.email }}
    </td>

    <td class="px-6 py-4">
      <button v-if="member.active === 1" class="text-green-400 text-xl font-semibold">Active</button>
      <button v-if="member.active === 0" class="text-gray-400 text-xl font-semibold" disabled>Inactive
      </button>
    </td>

    <td v-if="can.editTeam" class="px-6 py-4">
      <button v-if="can.manageTeam"
              class="bg-blue-600 text-white hover:bg-blue-500 ml-2 my-2 px-2 py-1 rounded disabled:bg-gray-400 h-max w-max"
              :class="managerButtonClass"
              @click.prevent="confirmTeamManager(props.member)"
      >
        <span>{{ managerButtonText }}</span>
      </button>

      <div v-if="$page.props.auth.user.id !== member.id || teamStore.team.teamOwner.id !== member.id">
        <button v-if="can.isTeamOwner || can.isTeamLeader"
                class="bg-red-600 text-white hover:bg-red-500 ml-2 my-2 px-2 py-1 rounded disabled:bg-gray-400 h-max w-max"
                @click.prevent="deleteTeamMember(member)"
        >
          Remove
        </button>
      </div>
    </td>

  </tr>

  <ConfirmRemoveTeamMemberDialog :member="props.member" @confirmDelete="teamStore.deleteTeamMember"/>
  <ConfirmTeamManagerDialog :member="props.member" @confirmRemoveManager="teamStore.removeTeamManager"
                            @confirmAddManager="teamStore.addTeamManager">
    <span v-if="teamStore.addManager">add</span>
    <span v-if="!teamStore.addManager">remove</span>
  </ConfirmTeamManagerDialog>

</template>

<script setup>
import { computed } from 'vue'
import { useTeamStore } from '@/Stores/TeamStore'
import ConfirmRemoveTeamMemberDialog from '@/Components/Global/Modals/ConfirmRemoveTeamMemberDialog'
import ConfirmTeamManagerDialog from '@/Components/Global/Modals/ConfirmTeamManagerDialog'

const teamStore = useTeamStore()

let props = defineProps({
  member: Object,
  can: Object,
})

teamStore.confirmDialog = false

function deleteTeamMember(member) {
  teamStore.deleteMemberName = member.name
  teamStore.deleteMemberId = member.id
  teamStore.confirmDialog = true
}

function confirmTeamManager(member) {
  teamStore.selectedManagerName = member.name
  teamStore.selectedManagerId = member.id
  teamStore.confirmManagerDialog = true

  teamStore.addManager = !teamStore.team.managers.some(manager => manager.id === member.id)
}

// Computed property to check if the member is a manager
const isManager = computed(() => {
  return teamStore.team.managers.some(manager => manager.id === props.member.id)
})

// Computed property for button text
const managerButtonText = computed(() => {
  return isManager.value ? 'Remove Manager' : 'Make Manager'
})

// Computed property for button class
const managerButtonClass = computed(() => {
  return isManager.value ? 'bg-orange-600 hover:bg-orange-500' : 'bg-blue-600 hover:bg-blue-500'
})

defineEmits([
  'removeMember',
])
</script>
