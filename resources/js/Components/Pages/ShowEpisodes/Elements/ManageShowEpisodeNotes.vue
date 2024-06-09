<template>
  <div class="shadow overflow-hidden bg-gray-100 border-b border-gray-200 sm:rounded-lg p-5 my-8">

    <div class="my-6 ml-10 md:w-3/4">
      <div class="mb-2">
        <span class="text-sm font-semibold uppercase">Internal Episode Notes (Team Only)</span>
        <span class="tooltip" data-tip="Only team members see these">
                                    <font-awesome-icon icon="fa-circle-info" class="ml-1"/>
                                </span>
      </div>
      {{ episode.notes }}
    </div>

  </div>

</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"

defineProps({
  episode: Object,
})
</script>
