<template>
  <div>
    <div class="mb-6 pb-6 flex justify-between border-b border-gray-800">
      <div class="mb-1 font-semibold text-xl dark:text-gray-50">
        My Shows
      </div>

      <div v-if="can.createShow" class="">

        <div v-if="hasShows">
          <Link :href="`/shows/create`">
            <button
                class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 text-xs rounded disabled:bg-gray-400"
            >Create Show
            </button>
          </Link>
        </div>

        <div v-else>
          <button class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 text-xs rounded disabled:bg-gray-400"
                  @click="createShowWithNoTeamButton">
            Create Show
          </button>
          <dialog id="dashboardNoTeams" class="modal">
            <div class="modal-box flex flex-col justify-center">
              <div class="font-bold text-lg mb-2 text-center">You don't have any teams!</div>
              <div class="w-full flex justify-center py-2">
                <button class="btn btn-primary w-fit px-4" @click="navigateToCreateTeam">Create a Team</button>
              </div>
              <div class="py-2 text-center">Press ESC key or click outside to close</div>
            </div>
            <form method="dialog" class="modal-backdrop">
              <button>close</button>
            </form>
          </dialog>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

defineProps({
  can: Object,
  hasShows: Boolean,
})

function createShowWithNoTeamButton() {
  document.getElementById('dashboardNoTeams').showModal()
}

const navigateToCreateTeam = () => {
  router.visit('teams/create')
};

</script>
