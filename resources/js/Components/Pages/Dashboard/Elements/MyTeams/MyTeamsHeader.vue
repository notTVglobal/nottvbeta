<template>
  <div>
    <div class="mb-6 pb-6 flex justify-between border-b border-gray-800">
      <div class="mb-1 font-semibold text-xl dark:text-gray-50">My Teams
      </div>

      <div v-if="can.createTeam" class="">
        <button @click="userStore.btnRedirect('/teams/create')"
                class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 text-xs rounded disabled:bg-gray-400"
        >New Team
        </button>
      </div>
    </div>

  </div>
</template>

<script setup>
import { useUserStore } from "@/Stores/UserStore"

const userStore = useUserStore()

defineProps({
  can: Object,
})

</script>
