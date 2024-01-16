<template>
  <div>
    <div v-if="!teams.data.length > 0" class="w-full text-center mb-4">
      <Popper
          hover
          openDelay="50"
          closeDelay="50"
          offset-skid="0"
          offset-distance="0"
          placement="top"
          arrow
      >
        <template #content>
          <p class="text-xl font-semibold mb-2">
            🎉 Start a new team</p>
          <p class="">
            Teams manage shows, </p>
          <p class="">
            even if you're a solo creator</p></template>
        <button class="rounded-full bg-black mt-12 w-12 h-12 hover:bg-pink-600 text-white hover:text-white">
          <font-awesome-icon
              icon="fa-solid fa-question"
              class="text-3xl"/>
        </button>
      </Popper>
    </div>

    <div v-if="teams.last_page >  1" class="">
      <Pagination :data="teams" class="mt-24 absolute inset-x-0 bottom-0 py-24 px-2"/>
    </div>

  </div>
</template>

<script setup>
import Pagination from "@/Components/Global/Paginators/Pagination"

defineProps({
  teams: Object,
})

</script>
