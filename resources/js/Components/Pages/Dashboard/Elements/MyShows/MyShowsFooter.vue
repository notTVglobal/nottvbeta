<template>
  <div>

    <div v-if="shows.data.length < 1" class="w-full text-center mb-12">
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
            🍿 These are your shows </p>
          <p class="">
            Join or create a team to start a show.</p></template>
        <button
            class="rounded-full bg-black mt-12 w-12 h-12 hover:bg-pink-600 text-white hover:text-white">
          <font-awesome-icon
              icon="fa-solid fa-question"
              class="text-3xl"/>
        </button>
      </Popper>
    </div>

    <div v-if="shows.last_page >  1" class="">
<!--      <Pagination :data="shows" class="inset-x-0 bottom-0 px-2"/>-->
      <PaginationDark :data="shows" class="text-white -ml-6"/>
    </div>

  </div>
</template>

<script setup>
import PaginationDark from '@/Components/Global/Paginators/PaginationDark.vue'

defineProps({
  shows: Object,
})

</script>
