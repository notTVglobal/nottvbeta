<template>
  <div v-if="team?.nextBroadcast || team.public_message" class="flex flex-row justify-center w-full py-10 px-5">
    <div class="flex flex-row text-red-950 bg-yellow-300 w-full py-6 text-center align-middle">
      <div class="flex flex-col w-1/3 border-r border-black">
        <div v-if="team?.nextBroadcast">
          <p class="uppercase font-bold tracking-wider">
            Next Broadcast
          </p>
          <p class="text-lg">
            April 20, 2024
          </p>
          <p class="text-lg">
            7:30pm PT
          </p>
          <p class="text-lg">
            Duncan Townhall
          </p>
        </div>
        <div v-else>
          No broadcasts are currently scheduled.
        </div>

      </div>
      <div class="flex flex-col w-2/3 justify-center font-semibold">
        {{ team.public_message }}
      </div>

    </div>
  </div>
</template>
<script setup>
const props = defineProps({
  team: Object,
})
</script>