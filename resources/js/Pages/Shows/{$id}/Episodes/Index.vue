<template>
  <Head title="Create Episode"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black p-5 mb-10">

      TEST INDEX
    </div>
  </div>

</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useUserStore } from "@/Stores/UserStore"

usePageSetup('showEpisodesIndex')

const userStore = useUserStore()

</script>
