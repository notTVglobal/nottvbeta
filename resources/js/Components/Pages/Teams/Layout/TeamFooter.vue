<template>

  <div class="mt-6">
    <p v-show="! team.spotsRemaining" class="mt-12 py-4 text-right text-gray-600 italic px-2">There are no remaining
      team spots.</p>

    <!-- Paginator -->
    <!--                            <Pagination :links="`#`" class="mt-6"/>-->
    <div class="py-4 font-semibold text-center bg-orange-300">
      {{ team.spots }} Member Team
    </div>
  </div>

</template>

<script setup>
import { useTeamStore } from "@/Stores/TeamStore"

let team = useTeamStore()

</script>
