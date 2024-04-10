<template>

  <Teleport to="body">
    <Modal :show="teamStore.confirmDialog" @close="teamStore.confirmDialog = false">

      <template #header>
        Are you sure?
      </template>


      <template #default>
        You are about to remove <span class="font-bold">{{ teamStore.deleteMemberName }}</span> from the team.
      </template>
      <template #footer>
        <button @click.prevent="teamStore.deleteTeamMemberCancel"
                class="bg-gray-500 hover:bg-gray-600 py-2 px-4 text-white rounded-lg mr-2">Cancel
        </button>
        <button @click.prevent="deleteConfirmed" class="bg-blue-500 hover:bg-blue-600 py-2 px-4 text-white rounded-lg">
          Confirm
        </button>
      </template>
    </Modal>
  </Teleport>


</template>

<script setup>
import { useTeamStore } from "@/Stores/TeamStore"
import Modal from "@/Components/Global/Modals/Modal"

const teamStore = useTeamStore()

defineProps({
  member: Object,
})

const emit = defineEmits(['confirmDelete'])

function deleteConfirmed() {
  emit('confirmDelete')
}

</script>
