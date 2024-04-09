<template>
  <Head title="Create Episode"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black p-5 mb-10">

      INDEX OF ALL EPISODES FOR A SHOW (optional page) -> maybe turn this into an Admin/Episodes to search and display ALL EPISODES for ALL SHOWS?

    </div>
  </div>

</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'

usePageSetup('shows/slug/episodes/')

</script>
